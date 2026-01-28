<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Http\Controllers\FirmaDictaminadorController;
use App\Http\Controllers\DocenteFormsController;
use App\Models\UsersResponseForm1;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return view('welcome'); // O redirigir a login
        }

        $email = $user->email;

        // 1. Determinar Roles
        // Es docente si su tipo es docente O si está en la lista de configuración de docentes (para pruebas)
        $isDocente = $user->user_type === 'docente' || in_array($email, config('docentes.emails', []));
        
        // Es dictaminador si su tipo es dictaminador O si su email está en la lista de dictaminadores
        $isDictaminador = $user->user_type === 'dictaminador' || in_array($email, config('dictaminadores.emails', []));

        // 2. Lógica para usuarios con AMBOS roles
        if ($isDocente && $isDictaminador) {
            $now = Carbon::now();

            // Asumimos que 'docentes_evaluacion' es cuando entran los dictaminadores
            // 1. Intentar buscar en la tabla NUEVA (docentes_evaluation_dates)
            $dictaminadorPeriod = DB::table('docentes_evaluation_dates')
                ->where('type', 'dictaminadores_capturando_datos')
                ->orderBy('id', 'desc')
                ->first();
            
            // 2. Fallback: Si no hay datos en la nueva, buscar en la tabla ANTIGUA (evaluation_dates)
            if (!$dictaminadorPeriod) {
                $dictaminadorPeriod = DB::table('evaluation_dates')
                    ->where('type', 'docentes_evaluacion')
                    ->orderBy('id', 'desc')
                    ->first();
            }

            // Si estamos en fecha de evaluación de dictaminadores -> Vista Dictaminador
            if ($dictaminadorPeriod && $now->between(Carbon::parse($dictaminadorPeriod->start_date)->startOfDay(), Carbon::parse($dictaminadorPeriod->end_date)->endOfDay())) {
                // Redirigir a la ruta o controlador de dictaminadores
                return app(DocenteFormsController::class)->index();
            }

            // En cualquier otro caso (antes o después) -> Vista Docente con datos completos
            return $this->returnWelcomeView($user);
        }

        // 3. Lógica para usuarios SOLO Dictaminadores
        if ($isDictaminador) {
            return app(DocenteFormsController::class)->index();
        }

        // 4. Lógica para usuarios SOLO Docentes (o cualquier otro tipo)
        return $this->returnWelcomeView($user);
    }

    private function returnWelcomeView($user)
    {
        $periodo = UsersResponseForm1::calculateCurrentPeriod() ?? 'Periodo no definido';
        
        $form1 = UsersResponseForm1::where('user_id', $user->id)->first();
        $convocatoria = $form1 ? $form1->convocatoria : 'Convocatoria no asignada';

        // Cargar datos directamente del archivo docentes.php si es posible
        $docenteEmails = array_values(config('docentes.emails', []));
        $docenteNombres = array_values(config('docentes.nombres', []));
        $docenteAreas = array_values(config('docentes.areas', []));
        $docenteDeptos = array_values(config('docentes.departamentos', []));

        // Buscar índice normalizando a minúsculas para asegurar que se encuentren los datos
        $dIndex = array_search(strtolower($user->email), array_map('strtolower', $docenteEmails));

        // 1. Priorizar datos de UsersResponseForm1 si existen (ya sincronizados en login)
        $nombre = $form1 && $form1->nombre ? $form1->nombre : $user->name;
        $area = $form1 && $form1->area ? $form1->area : ($user->area ?? 'No definida');
        $departamento = $form1 && $form1->departamento ? $form1->departamento : ($user->departamento ?? 'No definido');

        // 2. Sobrescribir con datos del archivo de configuración SOLO si existen y son válidos
        if ($dIndex !== false) {
            $nombre = isset($docenteNombres[$dIndex]) && !empty($docenteNombres[$dIndex]) ? $docenteNombres[$dIndex] : $nombre;
            $area = isset($docenteAreas[$dIndex]) && !empty($docenteAreas[$dIndex]) ? $docenteAreas[$dIndex] : $area;
            $departamento = isset($docenteDeptos[$dIndex]) && !empty($docenteDeptos[$dIndex]) ? $docenteDeptos[$dIndex] : $departamento;
        }

        $areaOptions = ['Agropecuaria', 'Ciencias del Mar y Tierra', 'Ciencias Sociales y Humanidades'];
        $departamentoOptions = ['Agronomia', 'Ciencia animal y Conservación del habitat', 'Ciencias de la tierra', 'Ciencias Marinas y Costeras', 'Ciencias Sociales y Juridicas', 'Economia', 'Humanidades', 'Ingenieria en Pesquerias', 'Sistemas Computacionales'];

        return view('welcome', compact('user', 'periodo', 'convocatoria', 'nombre', 'area', 'departamento', 'areaOptions', 'departamentoOptions'));
    }
}
