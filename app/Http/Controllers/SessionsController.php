<?php
/**
 * nombre del programador: Jose Antonio Martínez del Toro
 * objetivo: Controlador de sesiones
 * fecha: 2024-05-31
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\UsersResponseForm1; // Import UsersResponseForm1
use Illuminate\Support\Facades\Log;
use App\Models\EvaluationDate;
use Carbon\Carbon;

class SessionsController extends Controller
{
    public static $allowedEmails = [
        'joma_18@alu.uabcs.mx',
        'oa.campillo@uabcs.mx',
        'rluna@uabcs.mx',
        'v.andrade@uabcs.mx',
    ];
    public function index()
    {
        return view('login');
    }

    // Simulación de lista física de dictaminadores
    protected $dictaminadorEmails;

    public function __construct()
    {
        $this->dictaminadorEmails = config('dictaminadores.emails');
    }

public function login(Request $request)
{
    $email = strtolower(trim($request->input('email')));
    $password = $request->input('password');

    $isNoPassword = $request->input('no_password_required') == 'true';

    // --- ACCESO PARA USUARIOS SIN CONTRASEÑA (SECRETARIA) ---
    if (in_array($email, self::$allowedEmails) && $isNoPassword) {
        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $email,
                'user_type' => 'secretaria', // secretaria
                'email' => $email,
                'password' => Hash::make('defaultpassword'),
            ]);
        }

        Auth::login($user);

        return $this->redirectByUserType($user);
    }

    // --- ACCESO PARA DICTAMINADORES (desde config) ---
    // if (in_array($email, $this->dictaminadorEmails) && $isNoPassword) {
    if (in_array($email, $this->dictaminadorEmails)) {
        $user = User::where('email', $email)->first();

        // --- Lógica para determinar rol activo por fechas ---
        // 1. Por defecto asumimos rol de dictaminador (ya que entró por esta validación)
        $activeRole = 'dictaminador';
        
        // 2. Verificamos si TAMBIÉN es docente consultando el archivo de configuración
        $isAlsoDocente = in_array($email, config('docentes.emails', []));
        $now = Carbon::now();

        // 1. Buscar periodo de dictaminadores
        $dictaminadorPeriod = DB::table('docentes_evaluation_dates')
            ->where('type', 'dictaminadores_capturando_datos')
            ->orderBy('id', 'desc')
            ->first();

        // Fallback a tabla antigua si es necesario
        if (!$dictaminadorPeriod) {
            $dictaminadorPeriod = DB::table('evaluation_dates')
                ->where('type', 'docentes_evaluacion')
                ->orderBy('id', 'desc')
                ->first();
        }

        // 3. Si es usuario DUAL y NO estamos en periodo de dictaminación, cambiamos a rol docente
        $inDictaminatorPeriod = $dictaminadorPeriod && $now->between(Carbon::parse($dictaminadorPeriod->start_date)->startOfDay(), Carbon::parse($dictaminadorPeriod->end_date)->endOfDay());
        
        if ($isAlsoDocente && !$inDictaminatorPeriod) {
            $activeRole = 'docente';
        }
        // ---------------------------------------------------

        if (!$user) {
            $index = array_search($email, $this->dictaminadorEmails);
            $name = config('dictaminadores.nombres')[$index] ?? $email;
            $departamento = config('dictaminadores.departamentos')[$index] ?? null;

            $user = User::create([
                'name' => $name,
                'user_type' => $activeRole,
                'is_dictaminador' => true,
                'email' => $email,
                'password' => Hash::make('defaultpassword'),
                'departamento' => $departamento,
            ]);
        } else {
            $index = array_search($email, $this->dictaminadorEmails);
            $departamento = config('dictaminadores.departamentos')[$index] ?? $user->departamento;

            // Asegurarse de que el tipo sea dictaminador, flag esté activado y departamento actualizado
            $user->update([
                'user_type' => $activeRole,
                'is_dictaminador' => true,
                'departamento' => $departamento,
            ]);
        }

        // --- Asegurar que el usuario dual-role tenga un registro en UsersResponseForm1 ---
        // Esto es crucial ya que el formulario 1 fue eliminado de la vista.
        if ($isAlsoDocente) {
            $userResponseForm1 = UsersResponseForm1::firstOrNew(['user_id' => $user->id]);

            // Obtener datos desde docentes.php
            $docenteEmails = array_values(config('docentes.emails', []));
            $docenteNombres = array_values(config('docentes.nombres', []));
            $docenteAreas = array_values(config('docentes.areas', []));
            $docenteDeptos = array_values(config('docentes.departamentos', []));

            // Buscar índice normalizando a minúsculas para evitar errores de coincidencia
            $dIndex = array_search(strtolower($user->email), array_map('strtolower', $docenteEmails));
            
            $dNombre = ($dIndex !== false && isset($docenteNombres[$dIndex])) ? $docenteNombres[$dIndex] : $user->name;
            $dArea = ($dIndex !== false && isset($docenteAreas[$dIndex])) ? $docenteAreas[$dIndex] : ($user->area ?? 'No definida');
            $dDepto = ($dIndex !== false && isset($docenteDeptos[$dIndex])) ? $docenteDeptos[$dIndex] : ($user->departamento ?? 'No definido');

            if (!$userResponseForm1->exists) {
                $currentPeriod = UsersResponseForm1::calculateCurrentPeriod();
                // Intentar obtener la última convocatoria asignada, si no, usar un valor por defecto
                $latestForm1 = UsersResponseForm1::latest()->first();
                $currentConvocatoria = $latestForm1->convocatoria ?? 'Convocatoria no asignada';
                $userResponseForm1->periodo = $currentPeriod;
                $userResponseForm1->convocatoria = $currentConvocatoria;
            }
            
            // Actualizar siempre los datos desde config para mantenerlos sincronizados
            $userResponseForm1->nombre = $dNombre;
            $userResponseForm1->area = $dArea;
            $userResponseForm1->departamento = $dDepto;
            $userResponseForm1->save();
            
            Log::info('Dual-role docente login data saved', [
                'user_id' => $user->id,
                'email' => $user->email,
                'nombre' => $dNombre,
                'area' => $dArea,
                'departamento' => $dDepto,
                'config_index' => $dIndex
            ]);
        }
        // --------------------------------------------------------------------------------

        // Auth::login($user);

        // return $this->redirectByUserType($user);
    }

    // --- LOGIN REGULAR CON CONTRASEÑA ---
    if (Auth::attempt(['email' => $email, 'password' => $password])) {
        $user = Auth::user();

        // Si el usuario es un docente (o dual-role que entra como docente),
        // asegurar que tenga un registro en UsersResponseForm1 si no lo tiene.
        if (in_array($user->email, config('docentes.emails', [])) || $user->user_type === 'docente') {
            $userResponseForm1 = UsersResponseForm1::firstOrNew(['user_id' => $user->id]);
            
            // Obtener datos desde docentes.php
            $docenteEmails = array_values(config('docentes.emails', []));
            $docenteNombres = array_values(config('docentes.nombres', []));
            $docenteAreas = array_values(config('docentes.areas', []));
            $docenteDeptos = array_values(config('docentes.departamentos', []));

            // Buscar índice normalizando a minúsculas
            $dIndex = array_search(strtolower($user->email), array_map('strtolower', $docenteEmails));
            
            $dNombre = ($dIndex !== false && isset($docenteNombres[$dIndex])) ? $docenteNombres[$dIndex] : $user->name;
            $dArea = ($dIndex !== false && isset($docenteAreas[$dIndex])) ? $docenteAreas[$dIndex] : ($user->area ?? 'No definida');
            $dDepto = ($dIndex !== false && isset($docenteDeptos[$dIndex])) ? $docenteDeptos[$dIndex] : ($user->departamento ?? 'No definido');

            if (!$userResponseForm1->exists) {
                $currentPeriod = UsersResponseForm1::calculateCurrentPeriod();
                $latestForm1 = UsersResponseForm1::latest()->first();
                $currentConvocatoria = $latestForm1->convocatoria ?? 'Convocatoria no asignada';
                $userResponseForm1->periodo = $currentPeriod;
                $userResponseForm1->convocatoria = $currentConvocatoria;
            }
            
            // Actualizar siempre los datos desde config para mantenerlos sincronizados
            $userResponseForm1->nombre = $dNombre;
            $userResponseForm1->area = $dArea;
            $userResponseForm1->departamento = $dDepto;
            $userResponseForm1->save();
            
            Log::info('Docente login data saved', [
                'user_id' => $user->id,
                'email' => $user->email,
                'nombre' => $dNombre,
                'area' => $dArea,
                'departamento' => $dDepto,
                'config_index' => $dIndex
            ]);
        }

        return $this->redirectByUserType($user);
    }

    return back()->withErrors([
        'email' => 'Credenciales incorrectas, por favor intente de nuevo',
        'password' => 'Credenciales incorrectas, por favor intente de nuevo',
    ]);
}

private function redirectByUserType($user)
{
    $noCache = 'no-cache, no-store, must-revalidate';
    $pragmaNoCache = 'no-cache';
    $expiresZero = '0';

    // Verificación de período para docentes al iniciar sesión
    if ($user->user_type === 'docente') {
        $evaluationDates = EvaluationDate::where('type', 'docentes_llenado')->latest('id')->first();
        $now = Carbon::now();

        if ($evaluationDates) {
            $startDate = Carbon::parse($evaluationDates->start_date);
            $endDate = Carbon::parse($evaluationDates->end_date)->endOfDay();

            if (!$now->between($startDate, $endDate)) {
                return response()->view('errors.period-closed', [], 403);
            }
        } else {
            return response()->view('errors.period-closed', ['message' => 'El período de evaluación aún no ha sido configurado.'], 403);
        }
    }

    if ($user->user_type === 'dictaminador') {
        // Redirigir a welcome (DashboardController) para que evalúe si es rol dual y las fechas activas
        return redirect()->route('welcome')
            ->header('Cache-Control', $noCache)
            ->header('Pragma', $pragmaNoCache)
            ->header('Expires', $expiresZero);
    } elseif ($user->user_type === 'secretaria') {
        return redirect()->route('secretaria')
            ->header('Cache-Control', $noCache)
            ->header('Pragma', $pragmaNoCache)
            ->header('Expires', $expiresZero);
    } else {
        return redirect()->route('welcome')
            ->header('Cache-Control', $noCache)
            ->header('Pragma', $pragmaNoCache)
            ->header('Expires', $expiresZero);
    }
}


    public function logout(Request $request)
    {
        Auth::logout();
               
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        \Log::info('Logout ejecutado correctamente.');

        return redirect()->route('login') // Redirige a la ruta nombrada 'login'
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
    public function welcome(Request $request)
    {
        $user = Auth::user();
        
        // Obtener datos guardados en UsersResponseForm1 (creado al login)
        $form1 = UsersResponseForm1::where('user_id', $user->id)->first();

        $periodo = $form1 ? $form1->periodo : (\App\Models\UsersResponseForm1::calculateCurrentPeriod() ?? 'Periodo no definido');
        $convocatoria = $form1 ? $form1->convocatoria : 'Convocatoria no asignada';
        
        // Cargar datos directamente del archivo docentes.php si es posible
        // Usamos array_values para asegurar que los índices sean numéricos y coincidan
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

        Log::info('Welcome page data prepared', [
            'user_id' => $user->id,
            'email' => $user->email,
            'nombre' => $nombre,
            'area' => $area,
            'departamento' => $departamento,
            'config_index' => $dIndex,
            'has_form1' => $form1 ? true : false
        ]);

        return view('welcome', compact('user', 'periodo', 'convocatoria', 'nombre', 'area', 'departamento'));
    }

    public function showLoginForm()
    {
        // Verifica si el modo oscuro está habilitado para este usuario
        return view('auth.login', ['darkMode' => session('dark_mode', false)]);
    }

    

}