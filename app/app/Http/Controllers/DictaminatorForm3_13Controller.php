<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\DictaminatorsResponseForm3_13;
use App\Models\UsersResponseForm3_13;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
class DictaminatorForm3_13Controller extends TransferController
{
    public function storeform313(Request $request)
    {

        try {
            $validatedData = $request->validate([
                'dictaminador_id' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_13' => 'required|numeric',
                'comision3_13' => 'required|numeric',
                'cantInicioFinanExt' => 'required|numeric',
                'subtotalInicioFinanExt' => 'required|numeric',
                'comisionInicioFinancimientoExt' => 'required|numeric',
                'cantInicioInvInterno' => 'required|numeric',
                'subtotalInicioInvInterno' => 'required|numeric',
                'comisionInicioInvInterno' => 'required|numeric',
                'cantReporteFinanciamExt' => 'required|numeric',
                'subtotalReporteFinanciamExt' => 'required|numeric',
                'comisionReporteFinanciamExt' => 'required|numeric',
                'cantReporteInvInt' => 'required|numeric',
                'subtotalReporteInvInt' => 'required|numeric',
                'comisionReporteInvInt' => 'required|numeric',
                'obsInicioFinancimientoExt' => 'nullable|string',
                'obsInicioInvInterno' => 'nullable|string',
                'obsReporteFinanciamExt' => 'nullable|string',
                'obsReporteInvInt' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
            ]);

            if (!isset($validatedData['score3_13'])) {
                $validatedData['score3_13'] = 0;
            }
            $validatedData['obsInicioFinancimientoExt'] = $validatedData['obsInicioFinancimientoExt'] ?? 'sin comentarios';
            $validatedData['obsInicioInvInterno'] = $validatedData['obsInicioInvInterno'] ?? 'sin comentarios';
            $validatedData['obsReporteFinanciamExt'] = $validatedData['obsReporteFinanciamExt'] ?? 'sin comentarios';
            $validatedData['obsReporteInvInt'] = $validatedData['obsReporteInvInt'] ?? 'sin comentarios';


            $validatedData['form_type'] = 'form3_13';

            $response = DictaminatorsResponseForm3_13::create($validatedData);

            // Actualizar automáticamente el modelo docente con la comision
            $this->updateUserResponseComision($validatedData['user_id'], $validatedData['comision3_13']);
            DB::table('dictaminador_docente')->insert([
                //'dictaminador_form_id' => $response->id, // Asegúrate de que este ID exista
                'user_id' => $validatedData['user_id'], // Asegúrate de que este ID exista
                'dictaminador_id' => $response->dictaminador_id,
                'form_type' => 'form3_13', // O el tipo de formulario correspondiente
                'docente_email' => $response->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->checkAndTransfer('DictaminatorsResponseForm3_13');

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

    public function getFormData313(Request $request)
    {
        try {
            $data = DictaminatorsResponseForm3_13::where('user_id', $request->query('user_id'))->first();
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
        $userResponse = UsersResponseForm3_13::where('user_id', $userId)->first();

        if ($userResponse) {
            $userResponse->comision3_13 = $comisionValue;
            $userResponse->save();
        }
    }
}

