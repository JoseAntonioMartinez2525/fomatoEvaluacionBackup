<?php

namespace App\Http\Controllers;

use App\Models\DictaminatorsResponseForm3_8_1;
use App\Models\UsersResponseForm3_8_1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

public function showForm3_8_1() {
    // Recupera el valor de puntajeMaximo
    $puntajeMaximo = DB::table('puntajes_maximos')
                        //->where('user_type', '')
                        ->where('clave', 'puntajeMaximo') // Cambiado a 'clave'
                        ->value('valor');

        // Verifica si existen datos de dictaminador o docente
        $existenDatosDictaminador = DictaminatorsResponseForm3_8_1::exists();
        $existenDatosDocente = UsersResponseForm3_8_1::exists();

        $mostrarSoloSpan = $existenDatosDictaminador || $existenDatosDocente;

        // Pasa el valor a la vista
        return view('form3_8_1', compact('puntajeMaximo', 'mostrarSoloSpan'));
    }
}
