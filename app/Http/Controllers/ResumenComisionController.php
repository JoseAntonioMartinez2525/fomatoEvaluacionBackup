<?php

namespace App\Http\Controllers;

use App\Models\DictaminatorsResponseForm2;
use App\Models\User;
use Auth;
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
        $userType = auth()->User->user_type;

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
        if ($userType === 'secretaria') {
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

public function getFirmasYResumen(Request $request)
{
    $user = Auth::user();

    // Si es secretaria/admin, obtiene todas las firmas
    if (in_array($user->user_type, ['admin', 'secretaria'])) {
        $firmas = DB::table('firma_dictaminadores')
            ->select(
                'id',
                'user_id',
                'evaluator_name',
                'evaluator_name_2',
                'evaluator_name_3',
                'signature_path',
                'signature_path_2',
                'signature_path_3'
            )
            ->get();
    } 
    // Si es dictaminador, solo obtiene su propia firma
    else if ($user->user_type === 'dictaminador') {
        $firmas = DB::table('firma_dictaminadores')
            ->where('user_id', $user->id)
            ->select(
                'id',
                'user_id',
                'evaluator_name',
                'signature_path'
            )
            ->get();
    } 
    else {
        return response()->json(['error' => 'No autorizado'], 403);
    }

    // Si también necesitas traer el resumen de comisión junto con firmas:
    $resumen = DB::table('users_final_resume')
        ->where('email', $user->email)
        ->first();

    return response()->json([
        'usuario' => $user->email,
        'user_type' => $user->user_type,
        'firmas' => $firmas,
        'resumen' => $resumen
    ]);
}

    /**
     * Actualiza el campo 'periodo' de todos los registros de UsersResponseForm1
     * con el periodo vigente configurado.
     */
    public function updatePeriods()
    {
        try {
            $periodo = \App\Models\UsersResponseForm1::calculateCurrentPeriod();
            
            if (!$periodo) {
                return response()->json(['success' => false, 'message' => 'No se ha configurado un periodo válido en Fechas.'], 400);
            }

            // Actualizar todos los registros existentes
            \App\Models\UsersResponseForm1::query()->update(['periodo' => $periodo]);

            return response()->json(['success' => true, 'message' => "Periodo '$periodo' asignado a todos los docentes correctamente."]);
        } catch (\Exception $e) {
            \Log::error('Error actualizando periodos: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al actualizar periodos.'], 500);
        }
    }

    /**
     * Actualiza el campo 'convocatoria' de todos los registros de UsersResponseForm1.
     */
    public function updateConvocatoria(Request $request)
    {
        try {
            $convocatoria = $request->input('convocatoria');
            
            if (!$convocatoria) {
                return response()->json(['success' => false, 'message' => 'El nombre de la convocatoria es obligatorio.'], 400);
            }

            // Actualizar todos los registros existentes
            \App\Models\UsersResponseForm1::query()->update(['convocatoria' => $convocatoria]);

            return response()->json(['success' => true, 'message' => "Convocatoria '$convocatoria' asignada a todos los docentes correctamente."]);
        } catch (\Exception $e) {
            \Log::error('Error actualizando convocatoria: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al actualizar la convocatoria.'], 500);
        }
    }

    /**
     * Obtiene el historial de fechas de evaluación para docentes.
     */
    public function getEvaluationDatesHistory()
    {
        $history = DB::table('evaluation_dates')
            ->where('type', 'docentes_llenado')
            ->orderBy('id', 'desc')
            ->get();
            
        return response()->json($history);
    }
}
