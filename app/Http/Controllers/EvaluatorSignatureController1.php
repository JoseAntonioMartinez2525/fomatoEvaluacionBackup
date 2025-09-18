<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\EvaluatorSignature;
use Illuminate\Http\Request;

class EvaluatorSignatureController1 extends Controller
{
    public function storeEvaluatorSignature(Request $request)
    {
        try {
            // Log de entrada de solicitud
            Log::info('Received request to store evaluator signature', $request->all());
            // Validate the request data
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'evaluator_name' => 'nullable|string|max:255',
                'evaluator_name_2' => 'nullable|string|max:255',
                'evaluator_name_3' => 'nullable|string|max:255',
                'firma1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'firma2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'firma3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'user_type' => 'nullable|in:docente,dictaminador'
            ]);
/*
          
                        */
            // Cargar o crear una entrada de firma de dictaminador
            $evaluatorSignature = EvaluatorSignature::updateOrCreate(
            [
                'user_id' => $validatedData['user_id'],
                'email' => $validatedData['email']
            ]);


            // Comprobar si existe espacio para otra firma
            if (!$evaluatorSignature->hasAvailableSignatureSlot()) {
                return response()->json([
                    'message' => 'Ya se han registrado las tres firmas necesarias.'
                ], 400);
            }

            // Comprobar si existe espacio para otro nombre de evaluador
            if (!$evaluatorSignature->hasAvailableEvaluatorName()) {
                return response()->json([
                    'message' => 'Ya se han registrado los tres nombres necesarios.'
                ], 400);
            }

            // Guardar las nuevas firmas
            $firmas = ['firma1', 'firma2', 'firma3'];
            foreach ($firmas as $index => $firma) {
                if ($request->hasFile($firma)) {
                    $signaturePath = $request->file($firma)->store('signatures', 'public');
                    $evaluatorSignature->addSignaturePath($signaturePath);
                }
            }

            //Guardar los nombres de los evaluadores
            $nombres = ['evaluator_name', 'evaluator_name_2', 'evaluator_name_3'];
            foreach ($nombres as $index => $nombre) {
                if ($request->filled($nombre)) {
                    $evaluatorSignature->addEvaluatorName($validatedData[$nombre]);
                }
            }

            $evaluatorSignature->save();

            //verificar que los nombres se puedan repetir en caso de que se evalue a otro docente



            return response()->json([
                'message' => 'Firma guardada exitosamente.',
                'evaluator_names'=>[
                    'nombre de la persona evaluadora 1: ' => $evaluatorSignature->evaluator_name,
                    'nombre de la persona evaluadora 2: ' => $evaluatorSignature->evaluator_name_2,
                    'nombre de la persona evaluadora 3: ' => $evaluatorSignature->evaluator_name_3,
                ],
                'signature_urls' => [
                    'firma' => asset('storage/' . $evaluatorSignature->signature_path), 
                    'firma2' => asset('storage/' . $evaluatorSignature->signature_path_2),
                    'firma3' => asset('storage/' . $evaluatorSignature->signature_path_3),
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error storing evaluator signature', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'There was an error processing your request',
                'error' => $e->getMessage(),
            ], 500);
        }

    }

    public function getEvaluatorSignature(Request $request)
    {
        Log::info('Received request for evaluator signature', $request->all());
        
        // Validación de los campos del request
        $request->validate([
            'user_id' => 'exists:users,id',
            'email' => 'required|exists:users,email',
            
            /*
            'evaluator_name' => 'exists:evaluador_por_firmas,evaluator_name',
            'evaluator_name_2' => 'exists:evaluador_por_firmas,evaluator_name_2',
            'evaluator_name_3' => 'exists:evaluador_por_firmas,evaluator_name_3',
            'signature_path' => 'exists:evaluador_por_firmas,signature_path',
            'signature_path_2' => 'exists:evaluador_por_firmas,signature_path_2',
            'signature_path_3' => 'exists:evaluador_por_firmas,signature_path_3',
            */
            ]);

        $email = $request->input('email');
/*
        // Obtener el user_id ya sea del request o buscando por email
        if ($request->has('user_id')) {
            $userId = $request->input('user_id');
            //$userType = User::where('id', $userId)->value('user_type'); // Recuperar user_type por user_id
        } else {
            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'User not found',
                ], 404);
            }

            $userId = $user->id;
            //$userType = $user->user_type;
        }
*/
        $userId = $request->input('user_id');
        // Crear la consulta para EvaluatorSignature
        $evaluatorSignatureQuery = EvaluatorSignature::where('user_id', $userId)
            ->where('email', $email);

        // Si el user_type no está vacío, añadirlo a la consulta
        /*if ($userType) {
            $evaluatorSignatureQuery->where('user_type', $userType);
        }*/

        // Ejecutar la consulta y obtener el primer registro
        $evaluatorSignature = $evaluatorSignatureQuery->first();

        if (!$evaluatorSignature) {
            Log::warning('Evaluator signature not found', ['email' => $email]);

            return response()->json([
                'message' => "Evaluator signature not found for `$email` with id: `$userId`",
            ], 404);
        }

        Log::info('Evaluator signature data:', $evaluatorSignature->toArray());

        // Devolver los datos con las URLs completas de las firmas
        return response()->json([
            'user_id' => $evaluatorSignature->user_id,
            'email' => $evaluatorSignature->email,
            'evaluator_name' => $evaluatorSignature->evaluator_name,
            'evaluator_name_2' => $evaluatorSignature->evaluator_name_2,
            'evaluator_name_3' => $evaluatorSignature->evaluator_name_3,
            'signature_path' => asset('storage/' . $evaluatorSignature->signature_path),
            'signature_path_2' => asset('storage/' . $evaluatorSignature->signature_path_2),
            'signature_path_3' => asset('storage/' . $evaluatorSignature->signature_path_3),
        ]);
    }

}
