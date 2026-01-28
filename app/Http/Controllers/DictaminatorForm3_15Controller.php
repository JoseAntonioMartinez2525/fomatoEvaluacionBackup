<?php

namespace App\Http\Controllers;

use App\Models\DictaminatorsResponseForm3_15;
use App\Models\UsersResponseForm3_15;

use Illuminate\Http\Request;

class DictaminatorForm3_15Controller extends AbstractDictaminatorFormController
{
    /**
     * Devuelve el número del formulario
     */
    protected function getFormNumber(): string
    {
        return '3_15';
    }

    /**
     * Devuelve la clase del modelo de respuesta del dictaminador
     */
    protected function getDictaminatorModelClass(): string
    {
        return DictaminatorsResponseForm3_15::class;
    }

    /**
     * Devuelve la clase del modelo de respuesta del usuario
     */
    protected function getUserResponseModelClass(): string
    {
        return UsersResponseForm3_15::class;
    }

    /**
     * Devuelve los campos de observaciones
     */
    protected function getObservationFields(): array
    {
        return [
            'obsPatentes', 'obsPrototipos',
        ];
    }

    /**
     * Devuelve el nombre de la vista
     */
    protected function getViewName(): string
    {
        return 'form3_15';
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
                'score3_15' => 'required|numeric',
                'comision3_15' => 'required|numeric',
                'cantPatentes' => 'required|numeric',
                'subtotalPatentes' => 'required|numeric',
                'comisionPatententes' => 'required|numeric',
                'cantPrototipos' => 'required|numeric',
                'subtotalPrototipos' => 'required|numeric',
                'comisionPrototipos' => 'required|numeric',
                'obsPatentes' => 'nullable|string',
                'obsPrototipos' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
        ];
    }

    // Métodos alias para mantener compatibilidad con las rutas existentes
    public function storeform315(Request $request)
    {
        return $this->storeForm($request);
    }

    public function getFormData315(Request $request)
    {
        return $this->getFormData($request);
    }

    public function showForm315($teacherEmail = null)
    {
        return $this->showForm($teacherEmail);
    }

    public function updateform315(Request $request)
    {
        
        return $this->updateForm($request);
    }

}