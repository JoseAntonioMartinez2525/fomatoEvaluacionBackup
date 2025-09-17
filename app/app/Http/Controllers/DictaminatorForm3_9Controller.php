<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\DictaminatorsResponseForm3_9;
use App\Models\UsersResponseForm3_9;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
class DictaminatorForm3_9Controller extends TransferController
{
    public function storeform39(Request $request)
    {


        try {
            $validatedData = $request->validate([
                'dictaminador_id' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_9' => 'required|numeric',
                'comision3_9' => 'required|numeric',
                'puntaje3_9_1' => 'required|numeric',
                'puntaje3_9_2' => 'required|numeric',
                'puntaje3_9_3' => 'required|numeric',
                'puntaje3_9_4' => 'required|numeric',
                'puntaje3_9_5' => 'required|numeric',
                'puntaje3_9_6' => 'required|numeric',
                'puntaje3_9_7' => 'required|numeric',
                'puntaje3_9_8' => 'required|numeric',
                'puntaje3_9_9' => 'required|numeric',
                'puntaje3_9_10' => 'required|numeric',
                'puntaje3_9_11' => 'required|numeric',
                'puntaje3_9_12' => 'required|numeric',
                'puntaje3_9_13' => 'required|numeric',
                'puntaje3_9_14' => 'required|numeric',
                'puntaje3_9_15' => 'required|numeric',
                'puntaje3_9_16' => 'required|numeric',
                'puntaje3_9_17' => 'required|numeric',
                'tutorias1' => 'required|numeric',
                'tutorias2' => 'required|numeric',
                'tutorias3' => 'required|numeric',
                'tutorias4' => 'required|numeric',
                'tutorias5' => 'required|numeric',
                'tutorias6' => 'required|numeric',
                'tutorias7' => 'required|numeric',
                'tutorias8' => 'required|numeric',
                'tutorias9' => 'required|numeric',
                'tutorias10' => 'required|numeric',
                'tutorias11' => 'required|numeric',
                'tutorias12' => 'required|numeric',
                'tutorias13' => 'required|numeric',
                'tutorias14' => 'required|numeric',
                'tutorias15' => 'required|numeric',
                'tutorias16' => 'required|numeric',
                'tutorias17' => 'required|numeric',
                'tutoriasComision1' => 'required|numeric',
                'tutoriasComision2' => 'required|numeric',
                'tutoriasComision3' => 'required|numeric',
                'tutoriasComision4' => 'required|numeric',
                'tutoriasComision5' => 'required|numeric',
                'tutoriasComision6' => 'required|numeric',
                'tutoriasComision7' => 'required|numeric',
                'tutoriasComision8' => 'required|numeric',
                'tutoriasComision9' => 'required|numeric',
                'tutoriasComision10' => 'required|numeric',
                'tutoriasComision11' => 'required|numeric',
                'tutoriasComision12' => 'required|numeric',
                'tutoriasComision13' => 'required|numeric',
                'tutoriasComision14' => 'required|numeric',
                'tutoriasComision15' => 'required|numeric',
                'tutoriasComision16' => 'required|numeric',
                'tutoriasComision17' => 'required|numeric',
                'obs3_9_1' => 'nullable|string',
                'obs3_9_2' => 'nullable|string',
                'obs3_9_3' => 'nullable|string',
                'obs3_9_4' => 'nullable|string',
                'obs3_9_5' => 'nullable|string',
                'obs3_9_6' => 'nullable|string',
                'obs3_9_7' => 'nullable|string',
                'obs3_9_8' => 'nullable|string',
                'obs3_9_9' => 'nullable|string',
                'obs3_9_10' => 'nullable|string',
                'obs3_9_11' => 'nullable|string',
                'obs3_9_12' => 'nullable|string',
                'obs3_9_13' => 'nullable|string',
                'obs3_9_14' => 'nullable|string',
                'obs3_9_15' => 'nullable|string',
                'obs3_9_16' => 'nullable|string',
                'obs3_9_17' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
            ]);

            $validatedData['form_type'] = 'form3_9';

            if (!isset($validatedData['score3_9'])) {
                $validatedData['score3_9'] = 0;
            }
            $validatedData['obs3_9_1'] = $validatedData['obs3_9_1'] ?? 'sin comentarios';
            $validatedData['obs3_9_2'] = $validatedData['obs3_9_2'] ?? 'sin comentarios';
            $validatedData['obs3_9_3'] = $validatedData['obs3_9_3'] ?? 'sin comentarios';
            $validatedData['obs3_9_4'] = $validatedData['obs3_9_4'] ?? 'sin comentarios';
            $validatedData['obs3_9_5'] = $validatedData['obs3_9_5'] ?? 'sin comentarios';
            $validatedData['obs3_9_6'] = $validatedData['obs3_9_6'] ?? 'sin comentarios';
            $validatedData['obs3_9_7'] = $validatedData['obs3_9_7'] ?? 'sin comentarios';
            $validatedData['obs3_9_8'] = $validatedData['obs3_9_8'] ?? 'sin comentarios';
            $validatedData['obs3_9_9'] = $validatedData['obs3_9_9'] ?? 'sin comentarios';
            $validatedData['obs3_9_10'] = $validatedData['obs3_9_10'] ?? 'sin comentarios';
            $validatedData['obs3_9_11'] = $validatedData['obs3_9_11'] ?? 'sin comentarios';
            $validatedData['obs3_9_12'] = $validatedData['obs3_9_12'] ?? 'sin comentarios';
            $validatedData['obs3_9_13'] = $validatedData['obs3_9_13'] ?? 'sin comentarios';
            $validatedData['obs3_9_14'] = $validatedData['obs3_9_14'] ?? 'sin comentarios';
            $validatedData['obs3_9_15'] = $validatedData['obs3_9_15'] ?? 'sin comentarios';
            $validatedData['obs3_9_16'] = $validatedData['obs3_9_16'] ?? 'sin comentarios';
            $validatedData['obs3_9_17'] = $validatedData['obs3_9_17'] ?? 'sin comentarios';


            $response = DictaminatorsResponseForm3_9::create($validatedData);
            // Actualizar automáticamente el modelo docente con la comision
            $this->updateUserResponseComision($validatedData['user_id'], $validatedData['comision3_9']);
            DB::table('dictaminador_docente')->insert([
                //'dictaminador_form_id' => $response->id, // Asegúrate de que este ID exista
                'user_id' => $validatedData['user_id'], // Asegúrate de que este ID exista
                'dictaminador_id' => $response->dictaminador_id,
                'form_type' => 'form3_9', // O el tipo de formulario correspondiente
                'docente_email' => $response->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->checkAndTransfer('DictaminatorsResponseForm3_9');

            event(new EvaluationCompleted($validatedData['user_id']));

            return response()->json([
                'success' => true,
                'message' => 'Data successfully saved',
                'data' => $validatedData,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
            ], 500); // Cambiado de 900 a 500
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
            ], 500); // Cambiado de 900 a 500
        }

    }

    public function getFormData39(Request $request)
    {
        try {
            $data = DictaminatorsResponseForm3_9::where('user_id', $request->query('user_id'))->first();
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving data: ' . $e->getMessage(),
            ], 900);
        }

    }

    private function updateUserResponseComision($userId, $comisionValue)
    {
        // Buscar el registro de UsersResponseForm2 correspondiente y actualizar comision1
        $userResponse = UsersResponseForm3_9::where('user_id', $userId)->first();

        if ($userResponse) {
            $userResponse->comision3_9 = $comisionValue;
            $userResponse->save();
        }
    }
}

