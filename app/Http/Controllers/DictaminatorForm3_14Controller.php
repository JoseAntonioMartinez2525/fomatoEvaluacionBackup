<?php

namespace App\Http\Controllers;

use App\Models\DictaminatorsResponseForm3_14;
use App\Models\UsersResponseForm3_14;
use Illuminate\Http\Request;

class DictaminatorForm3_14Controller extends AbstractDictaminatorFormController
{
    /**
     * Devuelve el número del formulario
     */
    protected function getFormNumber(): string
    {
        return '3_14';
    }

    /**
     * Devuelve la clase del modelo de respuesta del dictaminador
     */
    protected function getDictaminatorModelClass(): string
    {
        return DictaminatorsResponseForm3_14::class;
    }

    /**
     * Devuelve la clase del modelo de respuesta del usuario
     */
    protected function getUserResponseModelClass(): string
    {
        return UsersResponseForm3_14::class;
    }

    /**
     * Devuelve los campos de observaciones
     */
    protected function getObservationFields(): array
    {
        return [
            'obsCongresoInt', 'obsCongresoNac', 'obsCongresoLoc',
        ];
    }

    /**
     * Devuelve el nombre de la vista
     */
    protected function getViewName(): string
    {
        return 'form3_14';
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
        ];
    }

    // Métodos alias para mantener compatibilidad con las rutas existentes
    public function storeform314(Request $request)
    {
        return $this->storeForm($request);
    }

    public function getFormData314(Request $request)
    {
        return $this->getFormData($request);
    }

    public function showForm314($teacherEmail = null)
    {
        return $this->showForm($teacherEmail);
    }

    public function updateform314(Request $request)
    {
        
        return $this->updateForm($request);
    }

}

