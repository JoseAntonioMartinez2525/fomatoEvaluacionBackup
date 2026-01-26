<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\UsersResponseForm2_2;
use Illuminate\Http\Request;
use App\Models\DictaminatorsResponseForm2_2;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Traits\ValidatesDictaminatorPeriod;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\alert;

class DictaminatorForm2_2Controller extends TransferController
{
    use ValidatesDictaminatorPeriod;

    public static function getValidationRules(): array
    {
        return [
                'dictaminador_id' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'hours' => 'required|numeric',
                'horasPosgrado' => 'required|numeric', // Allow nullable
                'horasSemestre' => 'required|numeric',
                'dse' => 'required|numeric',
                'dse2' => 'required|numeric',
                'comisionPosgrado' => 'required|numeric',
                'comisionLic' => 'required|numeric',
                'actv2Comision' => 'required|numeric',
                'obs2' => 'nullable|string',
                'obs2_2' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
        ];
    }

    public function storeform22(Request $request)
    {
        try {
            // 1. Obtener el ID del dictaminador autenticado y añadirlo al request.
            $dictaminadorId = \Auth::id();
            $request->merge(['dictaminador_id' => $dictaminadorId]);

            // 2. Llamar a la validación de fecha al inicio del método
            if ($error = $this->validateEvaluationPeriod($request, 'form2_2')) {
                return $error;
            }

            //3. validad formulario unico
             $this->validarFormularioUnico($request, 'dictaminators_response_form2_2');
            
             $validatedData = $request->validate(self::getValidationRules());

            // Actualizar el user_type del usuario si se proporciona
            $user = \App\Models\User::find($validatedData['user_id']);
            if ($user && isset($validatedData['user_type'])) {
                $user->user_type = $validatedData['user_type'];
                $user->save();
            }

            $validatedData['form_type'] = 'form2_2';


                if (!isset($validatedData['hours'])) {
                    $validatedData['hours'] = 0;
                }
                $validatedData['obs2'] = $validatedData['obs2'] ?? 'sin comentarios';
                $validatedData['obs2_2'] = $validatedData['obs2_2'] ?? 'sin comentarios';

                
                    // Esto actualiza si existe o crea si no existe
                    $response = DictaminatorsResponseForm2_2::updateOrCreate(
                        [
                            'dictaminador_id' => $dictaminadorId,
                            'user_id' => $validatedData['user_id']
                        ],
                        $validatedData
                    );

                $this->updateUserResponseComision($validatedData['user_id'], $validatedData['actv2Comision']);

                
                // Agregar a dictaminador_docente
                DB::table('dictaminador_docente')->updateOrInsert(
                    [
                        'docente_id' => $validatedData['user_id'],
                        'dictaminador_id' => $response->dictaminador_id,
                        'form_type' => 'form2_2',
                    ],
                    [
                        'docente_email' => $response->email,
                        'updated_at' => now(),
                ]);


                $this->checkAndTransfer('DictaminatorsResponseForm2_2');
                
                event(new EvaluationCompleted($validatedData['user_id']));
                
                return response()->json([
                        'success' => true,
                        'message' => 'Formulario enviado',
                        'data' => $validitatedData
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

public function getFormData22(Request $request)
{
    try {
        $query = DictaminatorsResponseForm2_2::query();

        if ($request->has('user_id')) {
            $query->where('user_id', $request->query('user_id'));
        } elseif ($request->has('email')) {
            $query->where('email', $request->query('email'));
        }

        $dictaminadorId = $request->query('dictaminador_id');

        // 1. Intentar obtener el registro del dictaminador actual
        $data = $dictaminadorId ? (clone $query)->where('dictaminador_id', $dictaminadorId)->first() : null;

        // 2. Si no se encuentra, buscar cualquier registro existente (de otro dictaminador) para prellenar
        if (!$data) {
            $data = $query->first();
        }

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
                'form2_2' => [],
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'form2_2' => [$data]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}


    private function updateUserResponseComision($userId, $comisionValue)
    {
        // Buscar el registro de UsersResponseForm2 correspondiente y actualizar comision1
        $userResponse = UsersResponseForm2_2::where('user_id', $userId)->first();

        if ($userResponse) {
            $userResponse->actv2Comision = $comisionValue;
            $userResponse->save();
        }
    }

        public function showForm2_2($teacherEmail = null)
    {
        // Si se proporciona un email de docente en la URL, no necesitamos mostrar el buscador.
        // El script de autocompletado cargará los datos automáticamente.
        $showSearchComponent = is_null($teacherEmail);

        $hasData = false;

        if ($teacherEmail) {
            $user = \App\Models\User::where('email', $teacherEmail)->first();
            if ($user) {
                $hasData = DictaminatorsResponseForm2_2::where('email', $teacherEmail)
                    ->exists(); // Se elimina el filtro por dictaminador_id para que sea true si alguien más ya evaluó
            }
        }
        return view('form2_2', [
            'teacherEmailFromUrl' => $teacherEmail,
            'showSearch' => $showSearchComponent,
            'hasData' => $hasData
        ]);
    }

     public function updateForm22(Request $request)
    {
        // Validar los datos de entrada
        $validatedData = $request->validate(self::getValidationRules());

        try {
            // Buscar el registro existente por user_id y dictaminador_id
            $response = DictaminatorsResponseForm2_2::updateOrCreate(
                [
                    'user_id' => $validatedData['user_id'],
                    'dictaminador_id' => $validatedData['dictaminador_id'],
                    'form_type' => $validatedData['form_type']
                ],
                $validatedData // Los datos con los que se actualizará o creará
            );

            return response()->json([
                'success' => true,
                'message' => 'Formulario actualizado correctamente.',
                'data' => $response
            ]);

        } catch (\Exception $e) {
            // Log del error para depuración
            \Log::error('Error al actualizar el formulario 2_2: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error en el servidor al actualizar.'
            ], 500);
        }
    }
}