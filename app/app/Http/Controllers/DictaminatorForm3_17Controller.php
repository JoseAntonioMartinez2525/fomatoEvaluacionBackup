<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\DictaminatorsResponseForm3_17;
use App\Models\UsersResponseForm3_17;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
class DictaminatorForm3_17Controller extends TransferController
{
    public function storeform317(Request $request)
    {

        try {
            $validatedData = $request->validate([
                'dictaminador_id' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_17' => 'required|numeric',
                'comision3_17' => 'required|numeric',
                'cantDifusionExt' => 'required|numeric',
                'cantDifusionInt' => 'required|numeric',
                'cantRepDifusionExt' => 'required|numeric',
                'cantRepDifusionInt' => 'required|numeric',
                'subtotalDifusionExt' => 'required|numeric',
                'subtotalDifusionInt' => 'required|numeric',
                'subtotalRepDifusionExt' => 'required|numeric',
                'subtotalRepDifusionInt' => 'required|numeric',
                'comisionDifusionExt' => 'required|numeric',
                'comisionDifusionInt' => 'required|numeric',
                'comisionRepDifusionExt' => 'required|numeric',
                'comisionRepDifusionInt' => 'required|numeric',
                'obsDifusionExt' => 'nullable|string',
                'obsDifusionInt' => 'nullable|string',
                'obsRepDifusionExt' => 'nullable|string',
                'obsRepDifusionInt' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
            ]);

            if (!isset($validatedData['score3_17'])) {
                $validatedData['score3_17'] = 0;
            }
            $validatedData['obsDifusionExt'] = $validatedData['obsDifusionExt'] ?? 'sin comentarios';
            $validatedData['obsDifusionInt'] = $validatedData['obsDifusionInt'] ?? 'sin comentarios';
            $validatedData['obsRepDifusionExt'] = $validatedData['obsRepDifusionExt'] ?? 'sin comentarios';
            $validatedData['obsRepDifusionInt'] = $validatedData['obsRepDifusionInt'] ?? 'sin comentarios';

            $validatedData['form_type'] = 'form3_17';

            $response = DictaminatorsResponseForm3_17::create($validatedData);
            
            // Actualizar automáticamente el modelo docente con la comision
            $this->updateUserResponseComision($validatedData['user_id'], $validatedData['comision3_17']);
            DB::table('dictaminador_docente')->insert([
                //'dictaminador_form_id' => $response->id, // Asegúrate de que este ID exista
                'user_id' => $validatedData['user_id'], // Asegúrate de que este ID exista
                'dictaminador_id' => $response->dictaminador_id,
                'form_type' => 'form3_17', // O el tipo de formulario correspondiente
                'docente_email' => $response->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->checkAndTransfer('DictaminatorsResponseForm3_17');

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
            ], 500); 
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
            ], 500); 
        }

    }

    public function getFormData317(Request $request)
    {
        try {
            $data = DictaminatorsResponseForm3_17::where('user_id', $request->query('user_id'))->first();
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
            ], 500);
        }

    }

    private function updateUserResponseComision($userId, $comisionValue)
    {
        // Buscar el registro de UsersResponseForm2 correspondiente y actualizar comision1
        $userResponse = UsersResponseForm3_17::where('user_id', $userId)->first();

        if ($userResponse) {
            $userResponse->comision3_17 = $comisionValue;
            $userResponse->save();
        }
    }
}

