<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\DictaminatorsResponseForm3_2;
use App\Models\UsersResponseForm3_2;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Traits\ValidatesDictaminatorPeriod;
use Illuminate\Support\Facades\Auth;

class DictaminatorForm3_2Controller extends TransferController
{
    use ValidatesDictaminatorPeriod;
    
        /**
     * Devuelve las reglas de validación para el formulario 3.2.
     * @return array
     */
    public static function getValidationRules(): array
    {
        return [
                'dictaminador_id' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_2' => 'required|numeric',
                'comision3_2' => 'required|numeric',
                'r1' => 'required|numeric',
                'r2' => 'required|numeric',
                'r3' => 'required|numeric',
                'cant1' => 'required|numeric',
                'cant2' => 'required|numeric',
                'cant3' => 'required|numeric',
                'prom90_100' => 'nullable|numeric',
                'prom80_90' => 'nullable|numeric',
                'prom70_80' => 'nullable|numeric',
                'obs3_2_1' => 'nullable|string',
                'obs3_2_2' => 'nullable|string',
                'obs3_2_3' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
        ];
    }

    public function storeform32(Request $request)
    {
          
        try {
            // 1. Obtener el ID del dictaminador autenticado y añadirlo al request.
            $dictaminadorId = \Auth::id();
            $request->merge(['dictaminador_id' => $dictaminadorId]);

            // 2. Llamar a la validación de fecha al inicio del método
            if ($error = $this->validateEvaluationPeriod($request, 'form3_2')) {
                return $error;
            }

            //3. validad formulario unico
            // $this->validarFormularioUnico($request, 'dictaminators_response_form3_2');

            $validatedData = $request->validate(self::getValidationRules());


            if (!isset($validatedData['score3_2'])) {
                $validatedData['score3_2'] = 0;
            }
            
            // Asignar 0 por defecto si vienen nulos, ya que la BD no acepta NULL
            $validatedData['prom90_100'] = $validatedData['prom90_100'] ?? 0;
            $validatedData['prom80_90'] = $validatedData['prom80_90'] ?? 0;
            $validatedData['prom70_80'] = $validatedData['prom70_80'] ?? 0;

            $validatedData['obs3_2_1'] = $validatedData['obs3_2_1'] ?? 'sin comentarios';
            $validatedData['obs3_2_2'] = $validatedData['obs3_2_2'] ?? 'sin comentarios';
            $validatedData['obs3_2_3'] = $validatedData['obs3_2_3'] ?? 'sin comentarios';


            
            $response = DictaminatorsResponseForm3_2::updateOrCreate(
                [
                    'dictaminador_id' => $dictaminadorId,
                    'user_id' => $validatedData['user_id']
                ],
                $validatedData
            );

                // Actualizar automáticamente el modelo docente con la comision
                $this->updateUserResponseComision($validatedData['user_id'], $validatedData['comision3_2']);
                // Agregar a dictaminador_docente
                DB::table('dictaminador_docente')->updateOrInsert(
                    [
                        'docente_id' => $validatedData['user_id'],
                        'dictaminador_id' => $response->dictaminador_id,
                        'form_type' => 'form3_2',
                    ],
                    [
                        'docente_email' => $response->email,
                        'updated_at' => now(),
                ]);

                $this->checkAndTransfer('DictaminatorsResponseForm3_2');

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
                // 'message' => 'Error al enviar, formulario ya existente',
                'message' => 'Error de base de datos: ' . $e->getMessage(),
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

        public function getFormData32(Request $request)
        {
            try {
                \Log::info('DictaminatorForm3_2Controller::getFormData32 - Inicio', ['request' => $request->all()]);

                $query = DictaminatorsResponseForm3_2::query();

                if ($request->has('user_id')) {
                    $query->where('user_id', $request->query('user_id'));
                } elseif ($request->has('email')) {
                    $query->where('email', $request->query('email'));
                }

                $dictaminadorId = $request->query('dictaminador_id') ?? Auth::id();

                // Intentar obtener primero el registro del dictaminador actual
                $data = (clone $query)->where('dictaminador_id', $dictaminadorId)->first();

                if (!$data) {
                    \Log::info('DictaminatorForm3_2Controller::getFormData32 - No data for specific dictaminador, trying fallback');
                    $data = $query->first();
                }

                if (!$data) {
                    \Log::info('DictaminatorForm3_2Controller::getFormData32 - Data not found');
                    return response()->json([
                        'success' => false,
                        'hasData' => false,
                        'message' => 'Data not found',
                        'form3_2' => [],
                    ], 200);
                }

                \Log::info('DictaminatorForm3_2Controller::getFormData32 - Data found', ['id' => $data->id]);

                return response()->json([
                    'success' => true,
                    'hasData' => true,
                    'data' => $data,
                    'form3_2' => [$data]
                ]);
            } catch (\Exception $e) {
                \Log::error('DictaminatorForm3_2Controller::getFormData32 - Error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }
        }

    private function updateUserResponseComision($userId, $comisionValue)
    {
        // Buscar el registro de UsersResponseForm2 correspondiente y actualizar comision1
        $userResponse = UsersResponseForm3_2::where('user_id', $userId)->first();

        if ($userResponse) {
            $userResponse->forceFill(['comision3_2' => $comisionValue]);
            $userResponse->save();
        }
    }

    public function showForm32($teacherEmail = null)
    {
        // Si se proporciona un email de docente en la URL, no necesitamos mostrar el buscador.
        // El script de autocompletado cargará los datos automáticamente.
        $showSearchComponent = is_null($teacherEmail);

        $hasData = false;

        if ($teacherEmail) {
            $user = \App\Models\User::where('email', $teacherEmail)->first();
            if ($user) {
                $hasData = DictaminatorsResponseForm3_2::where('email', $teacherEmail)
                    ->exists();
            }
        }
        return view('form3_2', [
            'teacherEmailFromUrl' => $teacherEmail,
            'showSearch' => $showSearchComponent,
            'hasData' => $hasData
        ]);
    }

        public function updateform32(Request $request)
{
    // Validar los datos de entrada
    try {
        \Log::info('updateform32 called', $request->all());
        $validatedData = $request->validate(self::getValidationRules());
        
        // Asignar 0 por defecto si vienen nulos
        $validatedData['prom90_100'] = $validatedData['prom90_100'] ?? 0;
        $validatedData['prom80_90'] = $validatedData['prom80_90'] ?? 0;
        $validatedData['prom70_80'] = $validatedData['prom70_80'] ?? 0;

        \Log::info('Validation passed', $validatedData);

        // Buscar el registro existente por user_id y dictaminador_id
        \Log::info('Before updateOrCreate');
        $response = DictaminatorsResponseForm3_2::updateOrCreate(
            [
                'user_id' => $validatedData['user_id'],
                'dictaminador_id' => $validatedData['dictaminador_id']
            ],
            // Excluir 'user_type' para evitar MassAssignmentException
            collect($validatedData)->except('user_type')->toArray()
        );
        \Log::info('After updateOrCreate', ['response' => $response]);

        // Replicar la lógica de actualización de 'store'
        \Log::info('Before updateUserResponseComision');
        $this->updateUserResponseComision($validatedData['user_id'], $validatedData['comision3_2']);
        \Log::info('After updateUserResponseComision');

        \Log::info('Before dictaminador_docente updateOrInsert');
        DB::table('dictaminador_docente')->updateOrInsert(
            [
                'docente_id' => $validatedData['user_id'],
                'dictaminador_id' => $response->dictaminador_id,
                'form_type' => 'form3_2',
            ],
            [
                'docente_email' => $response->email,
                'updated_at' => now(),
            ]
        );
        \Log::info('After dictaminador_docente updateOrInsert');

        return response()->json([
            'success' => true,
            'message' => 'Formulario actualizado correctamente.',
            'data' => $response
        ]);

    } catch (\Exception $e) {
        // Log del error para depuración
        \Log::error('Error al actualizar el formulario 3.2: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Ocurrió un error en el servidor al actualizar.'
        ], 500);
    }
}
}
