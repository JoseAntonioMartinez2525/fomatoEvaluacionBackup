<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Traits\ValidatesDictaminatorPeriod;

abstract class AbstractDictaminatorFormController extends TransferController
{
    use ValidatesDictaminatorPeriod;

    /**
     * Devuelve el número del formulario (ej: '3_3', '3_4')
     */
    abstract protected function getFormNumber(): string;

    /**
     * Devuelve la clase del modelo de respuesta del dictaminador
     */
    abstract protected function getDictaminatorModelClass(): string;

    /**
     * Devuelve la clase del modelo de respuesta del usuario
     */
    abstract protected function getUserResponseModelClass(): string;

    /**
     * Devuelve las reglas de validación específicas del formulario
     */
    abstract public static function getValidationRules(): array;

    /**
     * Devuelve el nombre de la vista para mostrar el formulario
     */
    abstract protected function getViewName(): string;

    /**
     * Devuelve el nombre del campo de puntuación (ej: 'score3_3')
     */
    protected function getScoreFieldName(): string
    {
        return 'score' . str_replace('_', '_', $this->getFormNumber());
    }

    /**
     * Devuelve el nombre del campo de comisión (ej: 'comision3_3')
     */
    protected function getComisionFieldName(): string
    {
        return 'comision' . str_replace('_', '_', $this->getFormNumber());
    }

    /**
     * Devuelve los campos de observaciones específicos del formulario
     */
    abstract protected function getObservationFields(): array;

    /**
     * Almacena el formulario
     */
    public function storeForm(Request $request)
    {
        try {
            // 1. Obtener el ID del dictaminador autenticado y añadirlo al request.
            $dictaminadorId = \Auth::id();
            $request->merge(['dictaminador_id' => $dictaminadorId]);

            // 2. Llamar a la validación de fecha al inicio del método
            if ($error = $this->validateEvaluationPeriod($request, 'form' . $this->getFormNumber())) {
                return $error;
            }

            // 3. validar formulario único
            $this->validarFormularioUnico($request, 'dictaminators_response_form' . str_replace('.', '_', $this->getFormNumber()));

            $validatedData = $request->validate(static::getValidationRules());

            $validatedData['form_type'] = 'form' . $this->getFormNumber();

            // Establecer valor por defecto para el score si no existe
            $scoreField = $this->getScoreFieldName();
            if (!isset($validatedData[$scoreField])) {
                $validatedData[$scoreField] = 0;
            }

            // Procesar campos de observaciones
            $observationFields = $this->getObservationFields();
            foreach ($observationFields as $campo) {
                $validatedData[$campo] = trim($validatedData[$campo] ?? '') !== '' ? $validatedData[$campo] : 'sin comentarios';
            }

            $modelClass = $this->getDictaminatorModelClass();
            $response = $modelClass::updateOrCreate(
                [
                    'dictaminador_id' => $dictaminadorId,
                    'user_id' => $validatedData['user_id']
                ],
                $validatedData
            );

            // Actualizar automáticamente el modelo docente con la comisión
            $comisionField = $this->getComisionFieldName();
            $this->updateUserResponseComision($validatedData['user_id'], $validatedData[$comisionField]);

            // Agregar a dictaminador_docente
            DB::table('dictaminador_docente')->updateOrInsert(
                [
                    'docente_id' => $validatedData['user_id'],
                    'dictaminador_id' => $response->dictaminador_id,
                    'form_type' => 'form' . $this->getFormNumber(),
                ],
                [
                    'docente_email' => $response->email,
                    'updated_at' => now(),
                ]
            );

            $this->checkAndTransfer('Dictaminators' . $this->getModelNamePart());

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

    /**
     * Obtiene los datos del formulario
     */
    public function getFormData(Request $request)
    {
        try {
            $modelClass = $this->getDictaminatorModelClass();
            $query = $modelClass::query()
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

    /**
     * Actualiza la comisión del usuario
     */
    private function updateUserResponseComision($userId, $comisionValue)
    {
        $userResponseClass = $this->getUserResponseModelClass();
        $userResponse = $userResponseClass::where('user_id', $userId)->first();

        if ($userResponse) {
            $comisionField = $this->getComisionFieldName();
            $userResponse->$comisionField = $comisionValue;
            $userResponse->save();
        }
    }

    /**
     * Muestra el formulario
     */
    public function showForm($teacherEmail = null)
    {
        $showSearchComponent = is_null($teacherEmail);
        $userType = \Auth::user() ? \Auth::user()->user_type : null;

        return view($this->getViewName(), [
            'teacherEmailFromUrl' => $teacherEmail,
            'showSearch' => $showSearchComponent,
            'userType' => $userType,
        ]);
    }

    /**
     * Actualiza el formulario
     */
    public function updateForm(Request $request)
    {
        try {
            $validatedData = $request->validate(static::getValidationRules());

            $scoreField = $this->getScoreFieldName();
            if (!isset($validatedData[$scoreField])) {
                $validatedData[$scoreField] = 0;
            }

            $observationFields = $this->getObservationFields();
            foreach ($observationFields as $campo) {
                $validatedData[$campo] = isset($validatedData[$campo]) && trim($validatedData[$campo]) !== '' ? $validatedData[$campo] : 'sin comentarios';
            }

            $modelClass = $this->getDictaminatorModelClass();
            $response = $modelClass::updateOrCreate(
                [
                    'user_id' => $validatedData['user_id'],
                    'dictaminador_id' => $validatedData['dictaminador_id']
                ],
                collect($validatedData)->except('user_type')->toArray()
            );

            $comisionField = $this->getComisionFieldName();
            $this->updateUserResponseComision($validatedData['user_id'], $validatedData[$comisionField]);

            DB::table('dictaminador_docente')->updateOrInsert(
                [
                    'docente_id' => $validatedData['user_id'],
                    'dictaminador_id' => $response->dictaminador_id,
                    'form_type' => 'form' . $this->getFormNumber(),
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
            \Log::error('Error al actualizar el formulario ' . $this->getFormNumber() . ': ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error en el servidor al actualizar.'
            ], 500);
        }
    }

    /**
     * Obtiene el nombre del modelo para usar en checkAndTransfer
     * Convierte '3_3' -> 'ResponseForm3_3'
     */
    protected function getModelNamePart(): string
    {
        return 'ResponseForm' . $this->getFormNumber();
    }
}
