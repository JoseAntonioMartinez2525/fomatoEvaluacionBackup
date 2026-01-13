<?php

namespace App\Http\Controllers;


use App\Models\DictaminatorsResponseForm3_5;
use App\Models\UsersResponseForm3_5;
use Illuminate\Http\Request;

class DictaminatorForm3_5Controller extends AbstractDictaminatorFormController
{
   
    /**
     * Devuelve el número del formulario
     */
    protected function getFormNumber(): string
    {
        return '3_5';
    }

    /**
     * Devuelve la clase del modelo de respuesta del dictaminador
     */
    protected function getDictaminatorModelClass(): string
    {
        return DictaminatorsResponseForm3_5::class;
    }

    /**
     * Devuelve la clase del modelo de respuesta del usuario
     */
    protected function getUserResponseModelClass(): string
    {
        return UsersResponseForm3_5::class;
    }

    /**
     * Devuelve los campos de observaciones
     */
    protected function getObservationFields(): array
    {
        return ['obs3_5_1', 'obs3_5_2'];
    }

    /**
     * Devuelve el nombre de la vista
     */
    protected function getViewName(): string
    {
        return 'form3_5';
    }

    /**
     * Devuelve las reglas de validación para el formulario 3.3.
     * @return array
     */
    public static function getValidationRules(): array
    {
        return [
                'dictaminador_id' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_5' => 'required|numeric',
                'comision3_5' => 'required|numeric',
                'cantDA' => 'required|numeric',
                'cantCAAC' => 'required|numeric',
                'cantDA2' => 'required|numeric',
                'cantCAAC2' => 'required|numeric',
                'comDA' => 'required|numeric',
                'comNCAA' => 'required|numeric',
                'obs3_5_1' => 'nullable|string',
                'obs3_5_2' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
        ];
    }

    // Métodos alias para mantener compatibilidad con las rutas existentes
    public function storeform35(Request $request)
    {
        return $this->storeForm($request);
    }

    public function getFormData35(Request $request)
    {
        return $this->getFormData($request);
    }

    public function showForm35($teacherEmail = null)
    {
        return $this->showForm($teacherEmail);
    }

    public function updateform35(Request $request)
    {
        return $this->updateForm($request);
    }

}

