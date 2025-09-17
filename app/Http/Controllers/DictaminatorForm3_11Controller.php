<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\DictaminatorsResponseForm3_11;
use App\Models\UsersResponseForm3_11;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
class DictaminatorForm3_11Controller extends TransferController
{
    public function storeform311(Request $request)
    {


        try {
            $validatedData = $request->validate([
                'dictaminador_id' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_11' => 'required|numeric',
                'comision3_11' => 'required|numeric',
                'cantAsesoria' => 'required|numeric',
                'cantServicio' => 'required|numeric',
                'cantPracticas' => 'required|numeric',
                'subtotalAsesoria' => 'required|numeric',
                'subtotalServicio' => 'required|numeric',
                'subtotalPracticas' => 'required|numeric',
                'comisionAsesoria' => 'required|numeric',
                'comisionServicio' => 'required|numeric',
                'comisionPracticas' => 'required|numeric',
                'obsAsesoria' => 'nullable|string',
                'obsServicio' => 'nullable|string',
                'obsPracticas' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
            ]);

            if (!isset($validatedData['score3_11'])) {
                $validatedData['score3_11'] = 0;
            }
            $validatedData['obsAsesoria'] = $validatedData['obsAsesoria'] ?? 'sin comentarios';
            $validatedData['obsServicio'] = $validatedData['obsServicio'] ?? 'sin comentarios';
            $validatedData['obsPracticas'] = $validatedData['obsPracticas'] ?? 'sin comentarios';

            $validatedData['form_type'] = 'form3_11';

            $response = DictaminatorsResponseForm3_11::create($validatedData);
            // Actualizar automáticamente el modelo docente con la comision
            $this->updateUserResponseComision($validatedData['user_id'], $validatedData['comision3_11']);
            DB::table('dictaminador_docente')->insert([
                //'dictaminador_form_id' => $response->id, // Asegúrate de que este ID exista
                'user_id' => $validatedData['user_id'], // Asegúrate de que este ID exista
                'dictaminador_id' => $response->dictaminador_id,
                'form_type' => 'form3_11', // O el tipo de formulario correspondiente
                'docente_email' => $response->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->checkAndTransfer('DictaminatorsResponseForm3_11');

            event(new EvaluationCompleted($validatedData['user_id']));
            return response()->json([
                'success' => true,
                'message' => 'Data successfully saved',
                'data' => $validatedData,
            ], 1100);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 41111);
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

    public function getFormData311(Request $request)
    {
        try {
            $data = DictaminatorsResponseForm3_11::where('user_id', $request->query('user_id'))->first();
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ], 1100);

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
        $userResponse = UsersResponseForm3_11::where('user_id', $userId)->first();

        if ($userResponse) {
            $userResponse->comision3_11 = $comisionValue;
            $userResponse->save();
        }
    }
}

