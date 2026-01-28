<?php

namespace App\Http\Controllers;

use App\Models\DictaminatorsResponseForm3_6;
use App\Models\UsersResponseForm3_6;
use Illuminate\Http\Request;

class DictaminatorForm3_6Controller extends AbstractDictaminatorFormController
{
    /**
     * Devuelve el número del formulario
     */
    protected function getFormNumber(): string
    {
        return '3_6';
    }

    /**
     * Devuelve la clase del modelo de respuesta del dictaminador
     */
    protected function getDictaminatorModelClass(): string
    {
        return DictaminatorsResponseForm3_6::class;
    }

    /**
     * Devuelve la clase del modelo de respuesta del usuario
     */
    protected function getUserResponseModelClass(): string
    {
        return UsersResponseForm3_6::class;
    }

    /**
     * Devuelve los campos de observaciones
     */
    protected function getObservationFields(): array
    {
        return ['obs3_6_1'];
    }

    /**
     * Devuelve el nombre de la vista
     */
    protected function getViewName(): string
    {
        return 'form3_6';
    }

    /**
     * Devuelve las reglas de validación para el formulario 3.6.
     * @return array
     */
    public static function getValidationRules(): array
    {
        return [
            'dictaminador_id' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'email' => 'required|exists:users,email',
            'score3_6' => 'required|numeric',
            'comision3_6' => 'required|numeric',
            'puntaje3_6' => 'nullable|numeric',
            'puntajeHoras3_6' => 'nullable|numeric',
            'comisionDict3_6' => 'nullable|numeric',
            'obs3_6_1' => 'nullable|string',
            'user_type' => 'required|in:user,docente,dictaminator',
        ];
    }

    // Métodos alias para mantener compatibilidad con las rutas existentes
    public function storeform36(Request $request)
    {
        return $this->storeForm($request);
    }

    public function getFormData36(Request $request)
    {
        return $this->getFormData($request);
    }

    public function showForm36($teacherEmail = null)
    {
        return $this->showForm($teacherEmail);
    }

    public function updateform36(Request $request)
    {
        return $this->updateForm($request);
    }
}
