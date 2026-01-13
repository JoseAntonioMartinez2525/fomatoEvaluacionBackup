<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\DictaminatorsResponseForm3_4;
use App\Models\UsersResponseForm3_4;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Traits\ValidatesDictaminatorPeriod;

class DictaminatorForm3_4Controller extends TransferController
{
    use ValidatesDictaminatorPeriod;
    
        public static function getValidationRules(): array
    {
        return [
                'dictaminador_id' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_4' => 'required|numeric',
                'comision3_4' => 'required|numeric',
                'cantInternacional' => 'nullable|numeric',
                'cantNacional' => 'nullable|numeric',
                'cantidadRegional' => 'nullable|numeric',
                'cantPreparacion' => 'nullable|numeric',
                'cantInternacional2' => 'nullable|numeric',
                'cantNacional2' => 'nullable|numeric',
                'cantidadRegional2' => 'nullable|numeric',
                'cantPreparacion2' => 'nullable|numeric',
                'comInternacional' => 'nullable|numeric',
                'comNacional' => 'nullable|numeric',
                'comRegional' => 'nullable|numeric',
                'comPreparacion' => 'nullable|numeric',
                'obs3_4_1' => 'nullable|string',
                'obs3_4_2' => 'nullable|string',
                'obs3_4_3' => 'nullable|string',
                'obs3_4_4' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
        ];
    }
    public function storeform34(Request $request)
    {

        try {
            // 1. Obtener el ID del dictaminador autenticado y añadirlo al request.
            $dictaminadorId = \Auth::id();
            $request->merge(['dictaminador_id' => $dictaminadorId]);

            // 2. Llamar a la validación de fecha al inicio del método
            if ($error = $this->validateEvaluationPeriod($request, 'form3_4')) {
                return $error;
            }
            
            //3. validad formulario unico
             $this->validarFormularioUnico($request, 'dictaminators_response_form3_4');
                

            $validatedData = $request->validate(self::getValidationRules());

            $validatedData['form_type'] = 'form3_4';

             if (!isset($validatedData['score3_3'])) {
                $validatedData['score3_3'] = 0;
            }

            $campos = ['obs3_4_1', 'obs3_4_2', 'obs3_4_3', 'obs3_4_4'];

            foreach ($campos as $campo) {
                $validatedData[$campo] = trim($validatedData[$campo]) !== '' ? $validatedData[$campo] : 'sin comentarios';
            }

            $response = DictaminatorsResponseForm3_4::updateOrCreate(
                [
                    'dictaminador_id' => $dictaminadorId,
                    'user_id' => $validatedData['user_id']
                ],
                $validatedData
            );
            // Actualizar automáticamente el modelo docente con la comision
            $this->updateUserResponseComision($validatedData['user_id'], $validatedData['comision3_4']);
           
                // Agregar a dictaminador_docente
                DB::table('dictaminador_docente')->updateOrInsert(
                    [
                        'docente_id' => $validatedData['user_id'],
                        'dictaminador_id' => $response->dictaminador_id,
                        'form_type' => 'form3_4',
                    ],
                    [
                        'docente_email' => $response->email,
                        'updated_at' => now(),
                ]);
            
            $this->checkAndTransfer('DictaminatorsResponseForm3_4');

            event(new EvaluationCompleted($validatedData['user_id']));
            
            return response()->json([
                        'success' => true,
                        'message' => 'Formulario enviado',
                        'data' => $validatedData
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
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
                    

    public function getFormData34(Request $request)
    {
        try {
            $query = DictaminatorsResponseForm3_4::query()
                ->where('dictaminador_id', $request->query('dictaminador_id'));

            if ($request->has('user_id')) {
                $query->where('user_id', $request->query('user_id'));
            } elseif ($request->has('email')) {
                $query->where('email', $request->query('email'));
            }

            $data = $query->first();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'hasData' => false,
                    'message' => 'Data not found',
                ], 200);
            }

            return response()->json([
                'success' => true,
                'hasData' => true,
                'data' => $data
            ]);

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
        $userResponse = UsersResponseForm3_4::where('user_id', $userId)->first();

        if ($userResponse) {
            $userResponse->comision3_4 = $comisionValue;
            $userResponse->save();
        }
    }

    public function showForm34($teacherEmail = null)
    {
        // Si se proporciona un email de docente en la URL, no necesitamos mostrar el buscador.
        // El script de autocompletado cargará los datos automáticamente.
        $showSearchComponent = is_null($teacherEmail);

        return view('form3_4', [
            'teacherEmailFromUrl' => $teacherEmail,
            'showSearch' => $showSearchComponent
        ]);
    }

    public function updateform34(Request $request)
    {
        // Validar los datos de entrada
        try {
            $validatedData = $request->validate(self::getValidationRules());

            if (!isset($validatedData['score3_4'])) {
                $validatedData['score3_4'] = 0;
            }

            $campos = ['obs3_4_1', 'obs3_4_2', 'obs3_4_3', 'obs3_4_4'];
            foreach ($campos as $campo) {
                $validatedData[$campo] = isset($validatedData[$campo]) && trim($validatedData[$campo]) !== '' ? $validatedData[$campo] : 'sin comentarios';
            }

            // Buscar el registro existente por user_id y dictaminador_id
            $response = DictaminatorsResponseForm3_4::updateOrCreate(
                [
                    'user_id' => $validatedData['user_id'],
                    'dictaminador_id' => $validatedData['dictaminador_id']
                ],
                collect($validatedData)->except('user_type')->toArray()
            );

            $this->updateUserResponseComision($validatedData['user_id'], $validatedData['comision3_3']);

            DB::table('dictaminador_docente')->updateOrInsert(
                [
                    'docente_id' => $validatedData['user_id'],
                    'dictaminador_id' => $response->dictaminador_id,
                    'form_type' => 'form3_4',
                ],
                [
                    'docente_email' => $response->email,
                    'updated_at' => now(),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Formulario actualizado correctamente.',
                'data' => $response
            ]);

        } catch (\Exception $e) {
            // Log del error para depuración
            \Log::error('Error al actualizar el formulario 3.3: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error en el servidor al actualizar.'
            ], 500);
        }
    }
}

