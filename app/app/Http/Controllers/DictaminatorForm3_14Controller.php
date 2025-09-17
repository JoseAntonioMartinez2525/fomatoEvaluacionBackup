<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\DictaminatorsResponseForm3_14;
use App\Models\UsersResponseForm3_14;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
class DictaminatorForm3_14Controller extends TransferController
{
    public function storeform314(Request $request)
    {

        try {
            $validatedData = $request->validate([
                'dictaminador_id' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_14' => 'required|numeric',
                'comision3_14' => 'required|numeric',
                'cantCongresoInt' => 'required|numeric',
                'subtotalCongresoInt' => 'required|numeric',
                'comisionCongresoInt' => 'required|numeric',
                'cantCongresoNac' => 'required|numeric',
                'subtotalCongresoNac' => 'required|numeric',
                'comisionCongresoNac' => 'required|numeric',
                'cantCongresoLoc' => 'required|numeric',
                'subtotalCongresoLoc' => 'required|numeric',
                'comisionCongresoLoc' => 'required|numeric',
                'obsCongresoInt' => 'nullable|string',
                'obsCongresoNac' => 'nullable|string',
                'obsCongresoLoc' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
            ]);

            if (!isset($validatedData['score3_14'])) {
                $validatedData['score3_14'] = 0;
            }
            $validatedData['obsCongresoInt'] = $validatedData['obsCongresoInt'] ?? 'sin comentarios';
            $validatedData['obsCongresoNac'] = $validatedData['obsCongresoNac'] ?? 'sin comentarios';
            $validatedData['obsCongresoLoc'] = $validatedData['obsCongresoLoc'] ?? 'sin comentarios';

            $validatedData['form_type'] = 'form3_14';

            $response = DictaminatorsResponseForm3_14::create($validatedData);

            // Actualizar automáticamente el modelo docente con la comision
            $this->updateUserResponseComision($validatedData['user_id'], $validatedData['comision3_14']);
            DB::table('dictaminador_docente')->insert([
                //'dictaminador_form_id' => $response->id, // Asegúrate de que este ID exista
                'user_id' => $validatedData['user_id'], // Asegúrate de que este ID exista
                'dictaminador_id' => $response->dictaminador_id,
                'form_type' => 'form3_14', // O el tipo de formulario correspondiente
                'docente_email' => $response->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->checkAndTransfer('DictaminatorsResponseForm3_14');

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
            ], 500); // Cambiado de 1200 a 500
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
            ], 500); // Cambiado de 1200 a 500
        }

    }

    public function getFormData314(Request $request)
    {
        try {
            $data = DictaminatorsResponseForm3_14::where('user_id', $request->query('user_id'))->first();
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
        $userResponse = UsersResponseForm3_14::where('user_id', $userId)->first();

        if ($userResponse) {
            $userResponse->comision3_14 = $comisionValue;
            $userResponse->save();
        }
    }
}

