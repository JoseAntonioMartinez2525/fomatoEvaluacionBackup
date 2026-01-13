<?php

namespace App\Http\Controllers;

use App\Models\DictaminatorsResponseForm3_3;
use App\Models\UsersResponseForm3_3;
use Illuminate\Http\Request;

class DictaminatorForm3_3Controller extends AbstractDictaminatorFormController
{
    /**
     * Devuelve el número del formulario
     */
    protected function getFormNumber(): string
    {
        return '3_3';
    }

    /**
     * Devuelve la clase del modelo de respuesta del dictaminador
     */
    protected function getDictaminatorModelClass(): string
    {
        return DictaminatorsResponseForm3_3::class;
    }

    /**
     * Devuelve la clase del modelo de respuesta del usuario
     */
    protected function getUserResponseModelClass(): string
    {
        return UsersResponseForm3_3::class;
    }

    /**
     * Devuelve los campos de observaciones
     */
    protected function getObservationFields(): array
    {
        return ['obs3_3_1', 'obs3_3_2', 'obs3_3_3', 'obs3_3_4'];
    }

    /**
     * Devuelve el nombre de la vista
     */
    protected function getViewName(): string
    {
        return 'form3_3';
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
                'score3_3' => 'required|numeric',
                'comision3_3' => 'required|numeric',
                'rc1' => 'nullable|numeric',
                'rc2' => 'nullable|numeric',
                'rc3' => 'nullable|numeric',
                'rc4' => 'nullable|numeric',
                'stotal1' => 'nullable|numeric',
                'stotal2' => 'nullable|numeric',
                'stotal3' => 'nullable|numeric',
                'stotal4' => 'nullable|numeric',
                'comIncisoA' => 'nullable|numeric',
                'comIncisoB' => 'nullable|numeric',
                'comIncisoC' => 'nullable|numeric',
                'comIncisoD' => 'nullable|numeric',
                'obs3_3_1' => 'nullable|string',
                'obs3_3_2' => 'nullable|string',
                'obs3_3_3' => 'nullable|string',
                'obs3_3_4' => 'nullable|string',               
                'user_type' => 'required|in:user,docente,dictaminator',
        ];
    }

    // Métodos alias para mantener compatibilidad con las rutas existentes
    public function storeform33(Request $request)
    {
        return $this->storeForm($request);
    }

    public function getFormData33(Request $request)
    {
        return $this->getFormData($request);
    }

    public function showForm33($teacherEmail = null)
    {
        return $this->showForm($teacherEmail);
    }

    public function updateform33(Request $request)
    {
        return $this->updateForm($request);
    }
}
