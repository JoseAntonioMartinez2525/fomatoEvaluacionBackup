<?php

namespace App\Http\Controllers;

use App\Models\DictaminatorsResponseForm3_16;
use App\Models\UsersResponseForm3_16;

use Illuminate\Http\Request;

class DictaminatorForm3_16Controller extends AbstractDictaminatorFormController
{
    /**
     * Devuelve el número del formulario
     */
    protected function getFormNumber(): string
    {
        return '3_16';
    }

    /**
     * Devuelve la clase del modelo de respuesta del dictaminador
     */
    protected function getDictaminatorModelClass(): string
    {
        return DictaminatorsResponseForm3_16::class;
    }

    /**
     * Devuelve la clase del modelo de respuesta del usuario
     */
    protected function getUserResponseModelClass(): string
    {
        return UsersResponseForm3_16::class;
    }

    /**
     * Devuelve los campos de observaciones
     */
    protected function getObservationFields(): array
    {
        return [
            'obsArbInt', 'obsArbNac', 'obsPubInt', 'obsPubNac', 'obsRevInt', 'obsRevNac', 'obsRevista',
        ];
    }

    /**
     * Devuelve el nombre de la vista
     */
    protected function getViewName(): string
    {
        return 'form3_16';
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
                'score3_16' => 'required|numeric',
                'comision3_16' => 'required|numeric',
                'cantArbInt' => 'required|numeric',
                'cantArbNac' => 'required|numeric',
                'cantPubInt' => 'required|numeric',
                'cantPubNac' => 'required|numeric',
                'cantRevInt' => 'required|numeric',
                'cantRevNac' => 'required|numeric',
                'cantRevista' => 'required|numeric',
                'subtotalArbInt' => 'required|numeric',
                'subtotalArbNac' => 'required|numeric',
                'subtotalPubInt' => 'required|numeric',
                'subtotalPubNac' => 'required|numeric',
                'subtotalRevInt' => 'required|numeric',
                'subtotalRevNac' => 'required|numeric',
                'subtotalRevista' => 'required|numeric',
                'comisionArbInt' => 'required|numeric',
                'comisionArbNac' => 'required|numeric',
                'comisionPubInt' => 'required|numeric',
                'comisionPubNac' => 'required|numeric',
                'comisionRevInt' => 'required|numeric',
                'comisionRevNac' => 'required|numeric',
                'comisionRevista' => 'required|numeric',
                'obsArbInt' => 'nullable|string',
                'obsArbNac' => 'nullable|string',
                'obsPubInt' => 'nullable|string',
                'obsPubNac' => 'nullable|string',
                'obsRevInt' => 'nullable|string',
                'obsRevNac' => 'nullable|string',
                'obsRevista' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
        ];
    }

    // Métodos alias para mantener compatibilidad con las rutas existentes
    public function storeform316(Request $request)
    {
        return $this->storeForm($request);
    }

    public function getFormData316(Request $request)
    {
        return $this->getFormData($request);
    }

    public function showForm316($teacherEmail = null)
    {
        return $this->showForm($teacherEmail);
    }

    public function updateform316(Request $request)
    {
        
        return $this->updateForm($request);
    }

}