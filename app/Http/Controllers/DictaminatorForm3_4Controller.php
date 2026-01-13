<?php

namespace App\Http\Controllers;

use App\Models\DictaminatorsResponseForm3_4;
use App\Models\UsersResponseForm3_4;
use Illuminate\Http\Request;

class DictaminatorForm3_4Controller extends AbstractDictaminatorFormController
{
    /**
     * Devuelve el número del formulario
     */
    protected function getFormNumber(): string
    {
        return '3_4';
    }

    /**
     * Devuelve la clase del modelo de respuesta del dictaminador
     */
    protected function getDictaminatorModelClass(): string
    {
        return DictaminatorsResponseForm3_4::class;
    }

    /**
     * Devuelve la clase del modelo de respuesta del usuario
     */
    protected function getUserResponseModelClass(): string
    {
        return UsersResponseForm3_4::class;
    }

    /**
     * Devuelve los campos de observaciones
     */
    protected function getObservationFields(): array
    {
        return ['obs3_4_1', 'obs3_4_2', 'obs3_4_3', 'obs3_4_4'];
    }

    /**
     * Devuelve el nombre de la vista
     */
    protected function getViewName(): string
    {
        return 'form3_4';
    }

    /**
     * Devuelve las reglas de validación para el formulario 3.4.
     * @return array
     */
    public static function getValidationRules(): array
    {
        return [
                'dictaminador_id' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_4' => 'required|numeric',
                'comision3_4' => 'required|numeric',
                'cantInternacional' => 'nullable|numeric',
                'cantNacional' => 'nullable|numeric',
                'cantidadRegional' => 'nullable|numeric',
                'cantPreparacion' => 'nullable|numeric',
                'cantInternacional2' => 'nullable|numeric',
                'cantNacional2' => 'nullable|numeric',
                'cantidadRegional2' => 'nullable|numeric',
                'cantPreparacion2' => 'nullable|numeric',
                'comInternacional' => 'nullable|numeric',
                'comNacional' => 'nullable|numeric',
                'comRegional' => 'nullable|numeric',
                'comPreparacion' => 'nullable|numeric',
                'obs3_4_1' => 'nullable|string',
                'obs3_4_2' => 'nullable|string',
                'obs3_4_3' => 'nullable|string',
                'obs3_4_4' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
        ];
    }

    // Métodos alias para mantener compatibilidad con las rutas existentes
    public function storeform34(Request $request)
    {
        return $this->storeForm($request);
    }

    public function getFormData34(Request $request)
    {
        return $this->getFormData($request);
    }

    public function showForm34($teacherEmail = null)
    {
        return $this->showForm($teacherEmail);
    }

    public function updateform34(Request $request)
    {
        return $this->updateForm($request);
    }
}

