<?php

namespace App\Http\Controllers;

use App\Models\DictaminatorsResponseForm3_8_1;
use App\Models\UsersResponseForm3_8_1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\UsersResponseForm1;

class PuntajeMaximosController extends Controller
{
public function updatePuntajeMaximo(Request $request)
{
    try {
        $request->validate([
            'puntajeMaximo' => 'required|numeric|min:0',
        ]);

        DB::table('puntajes_maximos')->updateOrInsert(
            ['clave' => 'puntajeMaximo'],
            ['valor' => $request->puntajeMaximo]
        );

        return response()->json(['message' => 'Puntaje máximo actualizado correctamente.']);
    } catch (\Exception $e) {
        //Log::error('Error al actualizar el puntaje máximo: ' . $e->getMessage());
        return response()->json(['message' => 'Error al actualizar el puntaje máximo.'], 500);
    }
}

public function showForm3_8_1(Request $request) {
    // Recupera el valor de puntajeMaximo
    $puntajeMaximo = DB::table('puntajes_maximos')
                        ->where('clave', 'puntajeMaximo') // Cambiado a 'clave'
                        ->value('valor');

    // Verifica si existen datos de dictaminador o docente
    $existenDatosDictaminador = DictaminatorsResponseForm3_8_1::exists();
    $existenDatosDocente = UsersResponseForm3_8_1::exists();

    $mostrarSoloSpan = $existenDatosDictaminador || $existenDatosDocente;

    // --- Lógica para proveer las variables que la vista requiere ---
    $teacherEmailFromUrl = $request->query('email');
    $showSearch = is_null($teacherEmailFromUrl);

    $targetUser = Auth::user();
    if ($teacherEmailFromUrl) {
        $found = \App\Models\User::where('email', $teacherEmailFromUrl)->first();
        if ($found) {
            $targetUser = $found;
        }
    }
    
    $form1 = UsersResponseForm1::where('user_id', $targetUser->id)->first();
    $periodo = ($form1 && $form1->periodo) ? $form1->periodo : (UsersResponseForm1::calculateCurrentPeriod() ?? 'Periodo no definido');
    $convocatoria = ($form1 && $form1->convocatoria) ? $form1->convocatoria : 'Convocatoria no asignada';

        // Data to pass to the view
        $viewData = compact('puntajeMaximo', 'mostrarSoloSpan', 'teacherEmailFromUrl', 'showSearch', 'convocatoria', 'periodo');
    $viewData['mostrarSoloSpan'] = $existenDatosDictaminador || $existenDatosDocente;

    // Pasa el valor a la vista
    return view('form3_8_1', compact(
        'puntajeMaximo', 
        'mostrarSoloSpan', 
        'teacherEmailFromUrl', 
        'showSearch', 
        'convocatoria', 
        'periodo'
    ));
    }
}
