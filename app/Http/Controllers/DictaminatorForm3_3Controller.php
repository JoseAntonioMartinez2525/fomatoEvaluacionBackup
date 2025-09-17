<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\DictaminatorsResponseForm3_3;
use App\Models\UsersResponseForm3_3;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DictaminatorForm3_3Controller extends TransferController
{
    public function storeform33(Request $request)
    {


        try {
            $validatedData = $request->validate([
                'dictaminador_id' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_3' => 'required|numeric',
                'comision3_3' => 'required|numeric',
                'rc1' => 'required|numeric',
                'rc2' => 'required|numeric',
                'rc3' => 'required|numeric',
                'rc4' => 'required|numeric',
                'stotal1' => 'required|numeric',
                'stotal2' => 'required|numeric',
                'stotal3' => 'required|numeric',
                'stotal4' => 'required|numeric',
                'comIncisoA' => 'required|numeric',
                'comIncisoB' => 'required|numeric',
                'comIncisoC' => 'required|numeric',
                'comIncisoD' => 'required|numeric',
                'obs3_3_1' => 'nullable|string',
                'obs3_3_2' => 'nullable|string',
                'obs3_3_3' => 'nullable|string',
                'obs3_3_4' => 'nullable|string',               
                'user_type' => 'required|in:user,docente,dictaminator',
            ]);

            $validatedData['form_type'] = 'form3_3';

            if (!isset($validatedData['score3_3'])) {
                $validatedData['score3_3'] = 0;
            }
            $validatedData['obs3_3_1'] = $validatedData['obs3_3_1'] ?? 'sin comentarios';
            $validatedData['obs3_3_2'] = $validatedData['obs3_3_2'] ?? 'sin comentarios';
            $validatedData['obs3_3_3'] = $validatedData['obs3_3_3'] ?? 'sin comentarios';
            $validatedData['obs3_3_4'] = $validatedData['obs3_3_4'] ?? 'sin comentarios';


            $response = DictaminatorsResponseForm3_3::create($validatedData);
            // Actualizar automáticamente el modelo docente con la comision
            $this->updateUserResponseComision($validatedData['user_id'], $validatedData['comision3_3']);
           
            DB::table('dictaminador_docente')->insert([
                'user_id' => $validatedData['user_id'], // Asegúrate de que este ID exista
                'dictaminador_id' => $response->dictaminador_id,
                'form_type' => 'form3_3', // O el tipo de formulario correspondiente
                'docente_email' => $response->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->checkAndTransfer('DictaminatorsResponseForm3_3');

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

    public function getFormData33(Request $request)
    {
        try {
            $data = DictaminatorsResponseForm3_3::where('user_id', $request->query('user_id'))->first();
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
        $userResponse = UsersResponseForm3_3::where('user_id', $userId)->first();

        if ($userResponse) {
            $userResponse->comision3_3 = $comisionValue;
            $userResponse->save();
        }
    }
}

