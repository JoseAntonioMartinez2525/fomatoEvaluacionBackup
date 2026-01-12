<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\DictaminatorsResponseForm3_13;
use App\Models\UsersResponseForm3_13;
use App\Traits\ValidatesDictaminatorPeriod;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class DictaminatorForm3_13Controller extends TransferController
{
    use ValidatesDictaminatorPeriod;
    public function storeform313(Request $request)
    {
        
        try {
            // 1. Obtener el ID del dictaminador autenticado y añadirlo al request.
            $dictaminadorId = \Auth::id();
            $request->merge(['dictaminador_id' => $dictaminadorId]);

            // 2. Llamar a la validación de fecha al inicio del método
            if ($error = $this->validateEvaluationPeriod($request, 'form3_13')) {
                return $error;
            }

            $validatedData = $request->validate([
                'dictaminador_id' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_13' => 'required|numeric',
                'comision3_13' => 'required|numeric',
                'cantInicioFinanExt' => 'nullable|numeric',
                'subtotalInicioFinanExt' => 'nullable|numeric',
                'comisionInicioFinancimientoExt' => 'nullable|numeric',
                'cantInicioInvInterno' => 'nullable|numeric',
                'subtotalInicioInvInterno' => 'nullable|numeric',
                'comisionInicioInvInterno' => 'nullable|numeric',
                'cantReporteFinanciamExt' => 'nullable|numeric',
                'subtotalReporteFinanciamExt' => 'nullable|numeric',
                'comisionReporteFinanciamExt' => 'nullable|numeric',
                'cantReporteInvInt' => 'nullable|numeric',
                'subtotalReporteInvInt' => 'nullable|numeric',
                'comisionReporteInvInt' => 'nullable|numeric',
                'obsInicioFinancimientoExt' => 'nullable|string',
                'obsInicioInvInterno' => 'nullable|string',
                'obsReporteFinanciamExt' => 'nullable|string',
                'obsReporteInvInt' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
            ]);

            if (!isset($validatedData['score3_13'])) {
                $validatedData['score3_13'] = 0;
            }
            
            // Asignar 0 por defecto si vienen nulos
            $numericNullableFields = [
                'cantInicioFinanExt', 'subtotalInicioFinanExt', 'comisionInicioFinancimientoExt',
                'cantInicioInvInterno', 'subtotalInicioInvInterno', 'comisionInicioInvInterno',
                'cantReporteFinanciamExt', 'subtotalReporteFinanciamExt', 'comisionReporteFinanciamExt',
                'cantReporteInvInt', 'subtotalReporteInvInt', 'comisionReporteInvInt'
            ];
            foreach ($numericNullableFields as $field) {
                $validatedData[$field] = $validatedData[$field] ?? 0;
            }

            $campos = ['obsInicioFinancimientoExt', 'obsInicioInvInterno', 'obsReporteFinanciamExt', 'obsReporteInvInt'];

            foreach ($campos as $campo) {
                $validatedData[$campo] = trim($validatedData[$campo]) !== '' ? $validatedData[$campo] : 'sin comentarios';
            }

            $validatedData['form_type'] = 'form3_13';
                // 3. VERIFICAR SI YA EXISTE UN REGISTRO PARA ESTE DICTAMINADOR Y DOCENTE
                $existingRecord = DictaminatorsResponseForm3_13::where('dictaminador_id', $dictaminadorId)
                    ->where('user_id', $validatedData['user_id'])
                    ->first();

                if ($existingRecord) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al enviar, formulario ya existente'
                    ], 409);
                }            

            $response = DictaminatorsResponseForm3_13::updateOrCreate(
                [
                    'dictaminador_id' => $dictaminadorId,
                    'user_id' => $validatedData['user_id']
                ],
                $validatedData
            );

            // Actualizar automáticamente el modelo docente con la comision
            $this->updateUserResponseComision($validatedData['user_id'], $validatedData['comision3_13']);
            DB::table('dictaminador_docente')->insert([
                //'dictaminador_form_id' => $response->id, // Asegúrate de que este ID exista
                'docente_id' => $validatedData['user_id'], // Asegúrate de que este ID exista
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
               'message' => 'Formulario enviado',
                'data' => $validatedData,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation fallida',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar, formulario ya existente',
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

        public function showForm313($teacherEmail = null)
    {
        // Si se proporciona un email de docente en la URL, no necesitamos mostrar el buscador.
        // El script de autocompletado cargará los datos automáticamente.
        $showSearchComponent = is_null($teacherEmail);

        return view('form3_13', [
            'teacherEmailFromUrl' => $teacherEmail,
            'showSearch' => $showSearchComponent
        ]);
    }
}
