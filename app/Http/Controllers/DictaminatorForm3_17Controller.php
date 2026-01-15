<?php

namespace App\Http\Controllers;

use App\Models\DictaminatorsResponseForm3_17;
use App\Models\UsersResponseForm3_17;

use Illuminate\Http\Request;

class DictaminatorForm3_17Controller extends AbstractDictaminatorFormController
{
    /**
     * Devuelve el número del formulario
     */
    protected function getFormNumber(): string
    {
        return '3_17';
    }

    /**
     * Devuelve la clase del modelo de respuesta del dictaminador
     */
    protected function getDictaminatorModelClass(): string
    {
        return DictaminatorsResponseForm3_17::class;
    }

    /**
     * Devuelve la clase del modelo de respuesta del usuario
     */
    protected function getUserResponseModelClass(): string
    {
        return UsersResponseForm3_17::class;
    }

    /**
     * Devuelve los campos de observaciones
     */
    protected function getObservationFields(): array
    {
        return [
            'obsDifusionExt', 'obsDifusionInt', 'obsRepDifusionExt', 'obsRepDifusionInt',
        ];
    }

    /**
     * Devuelve el nombre de la vista
     */
    protected function getViewName(): string
    {
        return 'form3_17';
    }

    /**
     * Devuelve las reglas de validación para el formulario 3.11.
     * @return array
     */
    public static function getValidationRules(): array
    {
        return [
                'dictaminador_id' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_17' => 'required|numeric',
                'comision3_17' => 'required|numeric',
                'cantDifusionExt' => 'required|numeric',
                'cantDifusionInt' => 'required|numeric',
                'cantRepDifusionExt' => 'required|numeric',
                'cantRepDifusionInt' => 'required|numeric',
                'subtotalDifusionExt' => 'required|numeric',
                'subtotalDifusionInt' => 'required|numeric',
                'subtotalRepDifusionExt' => 'required|numeric',
                'subtotalRepDifusionInt' => 'required|numeric',
                'comisionDifusionExt' => 'required|numeric',
                'comisionDifusionInt' => 'required|numeric',
                'comisionRepDifusionExt' => 'required|numeric',
                'comisionRepDifusionInt' => 'required|numeric',
                'obsDifusionExt' => 'nullable|string',
                'obsDifusionInt' => 'nullable|string',
                'obsRepDifusionExt' => 'nullable|string',
                'obsRepDifusionInt' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
        ];
    }

    // Métodos alias para mantener compatibilidad con las rutas existentes
    public function storeform317(Request $request)
    {
        return $this->storeForm($request);
    }

    public function getFormData317(Request $request)
    {
        return $this->getFormData($request);
    }

    public function showForm317($teacherEmail = null)
    {
        return $this->showForm($teacherEmail);
    }

    public function updateform317(Request $request)
    {
        
        return $this->updateForm($request);
    }

}