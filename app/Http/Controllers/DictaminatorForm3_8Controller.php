<?php

namespace App\Http\Controllers;


use App\Models\DictaminatorsResponseForm3_8;
use App\Models\UsersResponseForm3_8;
use Illuminate\Http\Request;

class DictaminatorForm3_8Controller extends AbstractDictaminatorFormController
{
    /**
     * Devuelve el número del formulario
     */
    protected function getFormNumber(): string
    {
        return '3_8';
    }

    /**
     * Devuelve la clase del modelo de respuesta del dictaminador
     */
    protected function getDictaminatorModelClass(): string
    {
        return DictaminatorsResponseForm3_8::class;
    }

    /**
     * Devuelve la clase del modelo de respuesta del usuario
     */
    protected function getUserResponseModelClass(): string
    {
        return UsersResponseForm3_8::class;
    }

    /**
     * Devuelve los campos de observaciones
     */
    protected function getObservationFields(): array
    {
        return ['obs3_8_1'];
    }

    /**
     * Devuelve el nombre de la vista
     */
    protected function getViewName(): string
    {
        return 'form3_8';
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
            'score3_8' => 'required|numeric',
            'comision3_8' => 'required|numeric',
            'comisionDict3_8' => 'required|numeric',
            'puntaje3_8' => 'required|numeric',
            'puntajeHoras3_8' => 'required|numeric',
            'obs3_8_1' => 'nullable|string',
            'user_type' => 'required|in:user,docente,dictaminator',
        ];
    }

    // Métodos alias para mantener compatibilidad con las rutas existentes
    public function storeform38(Request $request)
    {
        return $this->storeForm($request);
    }

    public function getFormData38(Request $request)
    {
        return $this->getFormData($request);
    }

    public function showForm38($teacherEmail = null)
    {
        return $this->showForm($teacherEmail);
    }

    public function updateform38(Request $request)
    {
        return $this->updateForm($request);
    }

}
