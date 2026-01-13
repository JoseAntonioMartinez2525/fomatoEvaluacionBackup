<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\DictaminatorsResponseForm3_12;
use App\Models\UsersResponseForm3_12;
use App\Traits\ValidatesDictaminatorPeriod;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class DictaminatorForm3_12Controller extends TransferController
{
    use ValidatesDictaminatorPeriod;
    public function storeform312(Request $request)
    {

        try {
            // 1. Obtener el ID del dictaminador autenticado y añadirlo al request.
            $dictaminadorId = \Auth::id();
            $request->merge(['dictaminador_id' => $dictaminadorId]);

            // 2. Llamar a la validación de fecha al inicio del método
            if ($error = $this->validateEvaluationPeriod($request, 'form3_12')) {
                return $error;
            }

            $validatedData = $request->validate([
                'dictaminador_id' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_12' => 'required|numeric',
                'comision3_12' => 'required|numeric',
                'cantCientifico' => 'nullable|numeric',
                'subtotalCientificos' => 'nullable|numeric',
                'comisionCientificos' => 'nullable|numeric',
                'cantDivulgacion' => 'nullable|numeric',
                'subtotalDivulgacion' => 'nullable|numeric',
                'comisionDivulgacion' => 'nullable|numeric',
                'cantTraduccion' => 'nullable|numeric',
                'subtotalTraduccion' => 'nullable|numeric',
                'comisionTraduccion' => 'nullable|numeric',
                'cantArbitrajeInt' => 'nullable|numeric',
                'subtotalArbitrajeInt' => 'nullable|numeric',
                'comisionArbitrajeInt' => 'nullable|numeric',
                'cantArbitrajeNac' => 'nullable|numeric',
                'subtotalArbitrajeNac' => 'nullable|numeric',
                'comisionArbitrajeNac' => 'nullable|numeric',
                'cantSinInt' => 'nullable|numeric',
                'subtotalSinInt' => 'nullable|numeric',
                'comisionSinInt' => 'nullable|numeric',
                'cantSinNac' => 'nullable|numeric',
                'subtotalSinNac' => 'nullable|numeric',
                'comisionSinNac' => 'nullable|numeric',
                'cantAutor' => 'nullable|numeric',
                'subtotalAutor' => 'nullable|numeric',
                'comisionAutor' => 'nullable|numeric',
                'cantEditor' => 'nullable|numeric',
                'subtotalEditor' => 'nullable|numeric',
                'comisionEditor' => 'nullable|numeric',
                'cantWeb' => 'nullable|numeric',
                'subtotalWeb' => 'nullable|numeric',
                'comisionWeb' => 'nullable|numeric',
                'obsCientificos' => 'nullable|string',
                'obsDivulgacion' => 'nullable|string',
                'obsTraduccion' => 'nullable|string',
                'obsArbitrajeInt' => 'nullable|string',
                'obsArbitrajeNac' => 'nullable|string',
                'obsSinInt' => 'nullable|string',
                'obsSinNac' => 'nullable|string',
                'obsAutor' => 'nullable|string',
                'obsEditor' => 'nullable|string',
                'obsWeb' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
            ]);

            if (!isset($validatedData['score3_12'])) {
                $validatedData['score3_12'] = 0;
            }
            //observaciones
            $campos = ['obsCientificos', 'obsDivulgacion', 'obsTraduccion', 'obsArbitrajeInt', 'obsArbitrajeNac','obsSinInt', 'obsSinNac', 'obsAutor', 'obsEditor', 'obsWeb'];

            foreach ($campos as $campo) {
                $validatedData[$campo] = trim($validatedData[$campo]) !== '' ? $validatedData[$campo] : 'sin comentarios';
            }

            $validatedData['form_type'] = 'form3_12';
                // 3. VERIFICAR SI YA EXISTE UN REGISTRO PARA ESTE DICTAMINADOR Y DOCENTE
                $existingRecord = DictaminatorsResponseForm3_12::where('dictaminador_id', $dictaminadorId)
                    ->where('user_id', $validatedData['user_id'])
                    ->first();

                if ($existingRecord) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al enviar, formulario ya existente'
                    ], 409);
                }            

            $response = DictaminatorsResponseForm3_12::updateOrCreate(
                [
                    'dictaminador_id' => $dictaminadorId,
                    'user_id' => $validatedData['user_id']
                ],
                $validatedData
            );
            // Actualizar automáticamente el modelo docente con la comision
            $this->updateUserResponseComision($validatedData['user_id'], $validatedData['comision3_12']);
            DB::table('dictaminador_docente')->insert([
                //'dictaminador_form_id' => $response->id, // Asegúrate de que este ID exista
                'docente_id' => $validatedData['user_id'], // Asegúrate de que este ID exista
                'dictaminador_id' => $response->dictaminador_id,
                'form_type' => 'form3_12', // O el tipo de formulario correspondiente
                'docente_email' => $response->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->checkAndTransfer('DictaminatorsResponseForm3_12');

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

    public function getFormData312(Request $request)
    {
        try {
                $query = DictaminatorsResponseForm3_12::query()
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
                    'message' => $e->getMessage(),
                ], 500);
            }
        }

    private function updateUserResponseComision($userId, $comisionValue)
    {
        // Buscar el registro de UsersResponseForm2 correspondiente y actualizar comision1
        $userResponse = UsersResponseForm3_12::where('user_id', $userId)->first();

        if ($userResponse) {
            $userResponse->comision3_12 = $comisionValue;
            $userResponse->save();
        }
    }

            public function showForm312($teacherEmail = null)
    {
        // Si se proporciona un email de docente en la URL, no necesitamos mostrar el buscador.
        // El script de autocompletado cargará los datos automáticamente.
        $showSearchComponent = is_null($teacherEmail);

        return view('form3_12', [
            'teacherEmailFromUrl' => $teacherEmail,
            'showSearch' => $showSearchComponent
        ]);
    }
}

