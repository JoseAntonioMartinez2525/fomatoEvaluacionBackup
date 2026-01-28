<?php

namespace App\Http\Controllers;

use App\Models\DictaminatorsResponseForm3_18;
use App\Models\UsersResponseForm3_18;

use Illuminate\Http\Request;

class DictaminatorForm3_18Controller extends AbstractDictaminatorFormController
{
    /**
     * Devuelve el número del formulario
     */
    protected function getFormNumber(): string
    {
        return '3_18';
    }

    /**
     * Devuelve la clase del modelo de respuesta del dictaminador
     */
    protected function getDictaminatorModelClass(): string
    {
        return DictaminatorsResponseForm3_18::class;
    }

    /**
     * Devuelve la clase del modelo de respuesta del usuario
     */
    protected function getUserResponseModelClass(): string
    {
        return UsersResponseForm3_18::class;
    }

    /**
     * Devuelve los campos de observaciones
     */
    protected function getObservationFields(): array
    {
        return [
            'obsComOrgInt', 'obsComOrgNac', 'obsComOrgReg', 'obsComApoyoInt', 'obsComApoyoNac', 'obsComApoyoReg', 'obsCicloComOrgInt', 'obsCicloComOrgNac', 'obsCicloComOrgReg', 'obsCicloComApoyoInt', 'obsCicloComApoyoNac', 'obsCicloComApoyoReg',
        ];
    }

    /**
     * Devuelve el nombre de la vista
     */
    protected function getViewName(): string
    {
        return 'form3_18';
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
                'score3_18' => 'required|numeric',
                'comision3_18' => 'required|numeric',
                'cantComOrgInt' => 'nullable|numeric',
                'cantComOrgNac' => 'nullable|numeric',
                'cantComOrgReg' => 'nullable|numeric',
                'cantComApoyoInt' => 'nullable|numeric',
                'cantComApoyoNac' => 'nullable|numeric',
                'cantComApoyoReg' => 'nullable|numeric',
                'cantCicloComOrgInt' => 'nullable|numeric',
                'cantCicloComOrgNac' => 'nullable|numeric',
                'cantCicloComOrgReg' => 'nullable|numeric',
                'cantCicloComApoyoInt' => 'nullable|numeric',
                'cantCicloComApoyoNac' => 'nullable|numeric',
                'cantCicloComApoyoReg' => 'nullable|numeric',
                'subtotalComOrgInt' => 'nullable|numeric',
                'subtotalComOrgNac' => 'nullable|numeric',
                'subtotalComOrgReg' => 'nullable|numeric',
                'subtotalComApoyoInt' => 'nullable|numeric',
                'subtotalComApoyoNac' => 'nullable|numeric',
                'subtotalComApoyoReg' => 'nullable|numeric',
                'subtotalCicloComOrgInt' => 'nullable|numeric',
                'subtotalCicloComOrgNac' => 'nullable|numeric',
                'subtotalCicloComOrgReg' => 'nullable|numeric',
                'subtotalCicloComApoyoInt' => 'nullable|numeric',
                'subtotalCicloComApoyoNac' => 'nullable|numeric',
                'subtotalCicloComApoyoReg' => 'nullable|numeric',
                'comisionComOrgInt' => 'nullable|numeric',
                'comisionComOrgNac' => 'nullable|numeric',
                'comisionComOrgReg' => 'nullable|numeric',
                'comisionComApoyoInt' => 'nullable|numeric',
                'comisionComApoyoNac' => 'nullable|numeric',
                'comisionComApoyoReg' => 'nullable|numeric',
                'comisionCicloComOrgInt' => 'nullable|numeric',
                'comisionCicloComOrgNac' => 'nullable|numeric',
                'comisionCicloComOrgReg' => 'nullable|numeric',
                'comisionCicloComApoyoInt' => 'nullable|numeric',
                'comisionCicloComApoyoNac' => 'nullable|numeric',
                'comisionCicloComApoyoReg' => 'nullable|numeric',
                'obsComOrgInt' => 'nullable|string',
                'obsComOrgNac' => 'nullable|string',
                'obsComOrgReg' => 'nullable|string',
                'obsComApoyoInt' => 'nullable|string',
                'obsComApoyoNac' => 'nullable|string',
                'obsComApoyoReg' => 'nullable|string',
                'obsCicloComOrgInt' => 'nullable|string',
                'obsCicloComOrgNac' => 'nullable|string',
                'obsCicloComOrgReg' => 'nullable|string',
                'obsCicloComApoyoInt' => 'nullable|string',
                'obsCicloComApoyoNac' => 'nullable|string',
                'obsCicloComApoyoReg' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
        ];
    }

    // Métodos alias para mantener compatibilidad con las rutas existentes
    public function storeform318(Request $request)
    {
        return $this->storeForm($request);
    }

    public function getFormData318(Request $request)
    {
        return $this->getFormData($request);
    }

    public function showForm318($teacherEmail = null)
    {
        return $this->showForm($teacherEmail);
    }

    public function updateform318(Request $request)
    {
        
        return $this->updateForm($request);
    }

}