<?php

namespace App\Http\Controllers;

use App\Models\DictaminatorsResponseForm3_9;
use App\Models\UsersResponseForm3_9;
use Illuminate\Http\Request;

class DictaminatorForm3_9Controller extends AbstractDictaminatorFormController
{
    /**
     * Devuelve el número del formulario
     */
    protected function getFormNumber(): string
    {
        return '3_9';
    }

    /**
     * Devuelve la clase del modelo de respuesta del dictaminador
     */
    protected function getDictaminatorModelClass(): string
    {
        return DictaminatorsResponseForm3_9::class;
    }

    /**
     * Devuelve la clase del modelo de respuesta del usuario
     */
    protected function getUserResponseModelClass(): string
    {
        return UsersResponseForm3_9::class;
    }

    /**
     * Devuelve los campos de observaciones
     */
    protected function getObservationFields(): array
    {
        return [
            'obs3_9_1', 'obs3_9_2', 'obs3_9_3', 'obs3_9_4', 'obs3_9_5',
            'obs3_9_6', 'obs3_9_7', 'obs3_9_8', 'obs3_9_9', 'obs3_9_10',
            'obs3_9_11', 'obs3_9_12', 'obs3_9_13', 'obs3_9_14', 'obs3_9_15',
            'obs3_9_16', 'obs3_9_17',
        ];
    }

    /**
     * Devuelve el nombre de la vista
     */
    protected function getViewName(): string
    {
        return 'form3_9';
    }

    /**
     * Devuelve las reglas de validación para el formulario 3.9.
     * @return array
     */
    public static function getValidationRules(): array
    {
        return [
            'dictaminador_id' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'email' => 'required|exists:users,email',
            'score3_9' => 'required|numeric',
            'comision3_9' => 'required|numeric',
            'puntaje3_9_1' => 'required|numeric',
            'puntaje3_9_2' => 'required|numeric',
            'puntaje3_9_3' => 'required|numeric',
            'puntaje3_9_4' => 'required|numeric',
            'puntaje3_9_5' => 'required|numeric',
            'puntaje3_9_6' => 'required|numeric',
            'puntaje3_9_7' => 'required|numeric',
            'puntaje3_9_8' => 'required|numeric',
            'puntaje3_9_9' => 'required|numeric',
            'puntaje3_9_10' => 'required|numeric',
            'puntaje3_9_11' => 'required|numeric',
            'puntaje3_9_12' => 'required|numeric',
            'puntaje3_9_13' => 'required|numeric',
            'puntaje3_9_14' => 'required|numeric',
            'puntaje3_9_15' => 'required|numeric',
            'puntaje3_9_16' => 'required|numeric',
            'puntaje3_9_17' => 'required|numeric',
            'tutorias1' => 'required|numeric',
            'tutorias2' => 'required|numeric',
            'tutorias3' => 'required|numeric',
            'tutorias4' => 'required|numeric',
            'tutorias5' => 'required|numeric',
            'tutorias6' => 'required|numeric',
            'tutorias7' => 'required|numeric',
            'tutorias8' => 'required|numeric',
            'tutorias9' => 'required|numeric',
            'tutorias10' => 'required|numeric',
            'tutorias11' => 'required|numeric',
            'tutorias12' => 'required|numeric',
            'tutorias13' => 'required|numeric',
            'tutorias14' => 'required|numeric',
            'tutorias15' => 'required|numeric',
            'tutorias16' => 'required|numeric',
            'tutorias17' => 'required|numeric',
            'tutoriasComision1' => 'required|numeric',
            'tutoriasComision2' => 'required|numeric',
            'tutoriasComision3' => 'required|numeric',
            'tutoriasComision4' => 'required|numeric',
            'tutoriasComision5' => 'required|numeric',
            'tutoriasComision6' => 'required|numeric',
            'tutoriasComision7' => 'required|numeric',
            'tutoriasComision8' => 'required|numeric',
            'tutoriasComision9' => 'required|numeric',
            'tutoriasComision10' => 'required|numeric',
            'tutoriasComision11' => 'required|numeric',
            'tutoriasComision12' => 'required|numeric',
            'tutoriasComision13' => 'required|numeric',
            'tutoriasComision14' => 'required|numeric',
            'tutoriasComision15' => 'required|numeric',
            'tutoriasComision16' => 'required|numeric',
            'tutoriasComision17' => 'required|numeric',
            'obs3_9_1' => 'nullable|string',
            'obs3_9_2' => 'nullable|string',
            'obs3_9_3' => 'nullable|string',
            'obs3_9_4' => 'nullable|string',
            'obs3_9_5' => 'nullable|string',
            'obs3_9_6' => 'nullable|string',
            'obs3_9_7' => 'nullable|string',
            'obs3_9_8' => 'nullable|string',
            'obs3_9_9' => 'nullable|string',
            'obs3_9_10' => 'nullable|string',
            'obs3_9_11' => 'nullable|string',
            'obs3_9_12' => 'nullable|string',
            'obs3_9_13' => 'nullable|string',
            'obs3_9_14' => 'nullable|string',
            'obs3_9_15' => 'nullable|string',
            'obs3_9_16' => 'nullable|string',
            'obs3_9_17' => 'nullable|string',
            'user_type' => 'required|in:user,docente,dictaminator',
        ];
    }

    // Métodos alias para mantener compatibilidad con las rutas existentes
    public function storeform39(Request $request)
    {
        return $this->storeForm($request);
    }

    public function getFormData39(Request $request)
    {
        return $this->getFormData($request);
    }

    public function showForm39($teacherEmail = null)
    {
        return $this->showForm($teacherEmail);
    }

    public function updateform39(Request $request)
    {
        return $this->updateForm($request);
    }
}

