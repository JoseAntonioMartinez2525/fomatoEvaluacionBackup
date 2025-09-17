<?php

namespace App\Http\Controllers;

use App\Models\DictaminatorsResponseForm2;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResumenComisionController extends Controller
{

    public function getDictaminadoresFinalData()
    {
        $dictaminador = User::where('user_type', 'dictaminador')->get(['id', 'email']);
        \Log::info('Dictaminador:', $dictaminador->toArray());
        return response()->json($dictaminador);
    }
    public function getDictaminadorFinalData(Request $request)
    {
        $email = $request->query('email');

        \Log::info('Email recibido:', ['email' => $email]);

        $dictaminador = User::where('email', $email)->first();

        if (!$dictaminador) {
            return response()->json(['error' => 'Dictaminador not found'], 404);
        }

        $formFinalData = DB::table('consolidated_responses')
            ->join('users_final_resume', 'consolidated_responses.user_email', '=', 'users_final_resume.email')
            ->where('consolidated_responses.user_email', $email)
            ->select('consolidated_responses.*', 'users_final_resume.*')
            ->first();

        

        if (!$formFinalData) {
            return response()->json(['error' => 'Datos del formulario no encontrados'], 404);
        }

        // Retornar siempre una respuesta JSON
        return response()->json($formFinalData);
    }

    public function fetchConvocatoria($user_id)
    {
        \Log::info('User ID recibido:', ['user_id' => $user_id]);

        // Obtener el tipo de usuario autenticado
        $userType = auth()->user()->user_type;

        // Si el usuario es 'dictaminador', obtenemos la convocatoria a través del docente relacionado
        if ($userType === 'dictaminador') {
            // Obtener el registro de DictaminatorsResponseForm2 relacionado al dictaminador
            $dictaminatorResponse = DictaminatorsResponseForm2::where('user_id', $user_id)->first();

            if (!$dictaminatorResponse) {
                return response()->json(['error' => 'No se encontró la respuesta para el dictaminador'], 404);
            }

            // Obtener la convocatoria a través de la relación con UsersResponseForm1 (del docente)
            $convocatoria = $dictaminatorResponse->UsersResponseForm1->convocatoria ?? 'No hay convocatoria';

            if (!$convocatoria) {
                return response()->json(['error' => 'Convocatoria no encontrada'], 404);
            }

            \Log::info('Convocatoria encontrada:', ['convocatoria' => $convocatoria]);

            return response()->json(['convocatoria' => $convocatoria]);
        }

        // Si el usuario es del tipo vacío (''), buscar los datos del dictaminador por defecto
        if ($userType === '') {
            // Aquí asumimos que el usuario '' tiene una relación o acceso por defecto al dictaminador
            // Debes definir cómo se obtiene el dictaminador en este caso. Asumo que tienes alguna manera de obtenerlo.

            $defaultDictaminatorResponse = DictaminatorsResponseForm2::where('dictaminador_id', $user_id)->first(); // Cambia esta lógica según tu estructura de relación

            if (!$defaultDictaminatorResponse) {
                return response()->json(['error' => 'No se encontró la respuesta para el dictaminador por defecto'], 404);
            }

            // Obtener la convocatoria a través de la relación con UsersResponseForm1 (del docente relacionado con el dictaminador)
            $convocatoria = $defaultDictaminatorResponse->UsersResponseForm1->convocatoria ?? 'No hay convocatoria';

            if (!$convocatoria) {
                return response()->json(['error' => 'Convocatoria no encontrada'], 404);
            }

            \Log::info('Convocatoria encontrada:', ['convocatoria' => $convocatoria]);

            return response()->json(['convocatoria' => $convocatoria]);
        }

        // Si el usuario no tiene permiso
        return response()->json(['error' => 'Access denied'], 403);
    }




}
