<?php

namespace App\Http\Controllers;

use App\Models\DictaminatorsResponseForm3_12;
use App\Models\UsersResponseForm3_12;

use Illuminate\Http\Request;


class DictaminatorForm3_12Controller extends AbstractDictaminatorFormController
{
    /**
     * Devuelve el número del formulario
     */
    protected function getFormNumber(): string
    {
        return '3_12';
    }

    /**
     * Devuelve la clase del modelo de respuesta del dictaminador
     */
    protected function getDictaminatorModelClass(): string
    {
        return DictaminatorsResponseForm3_12::class;
    }

    /**
     * Devuelve la clase del modelo de respuesta del usuario
     */
    protected function getUserResponseModelClass(): string
    {
        return UsersResponseForm3_12::class;
    }

    /**
     * Devuelve los campos de observaciones
     */
    protected function getObservationFields(): array
    {
        return [
            'obsCientificos', 'obsDivulgacion', 'obsTraduccion', 'obsArbitrajeInt', 'obsArbitrajeNac','obsSinInt', 'obsSinNac', 'obsAutor', 'obsEditor', 'obsWeb'
        ];
    }

    /**
     * Devuelve el nombre de la vista
     */
    protected function getViewName(): string
    {
        return 'form3_12';
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
                'score3_12' => 'required|numeric',
                'comision3_12' => 'required|numeric',
                'cantCientifico' => 'nullable|numeric',
                'subtotalCientificos' => 'nullable|numeric',
                'comisionCientificos' => 'nullable|numeric',
                'cantDivulgacion' => 'nullable|numeric',
                'subtotalDivulgacion' => 'nullable|numeric',
                'comisionDivulgacion' => 'nullable|numeric',
                'cantTraduccion' => 'nullable|numeric',
                'subtotalTraduccion' => 'nullable|numeric',
                'comisionTraduccion' => 'nullable|numeric',
                'cantArbitrajeInt' => 'nullable|numeric',
                'subtotalArbitrajeInt' => 'nullable|numeric',
                'comisionArbitrajeInt' => 'nullable|numeric',
                'cantArbitrajeNac' => 'nullable|numeric',
                'subtotalArbitrajeNac' => 'nullable|numeric',
                'comisionArbitrajeNac' => 'nullable|numeric',
                'cantSinInt' => 'nullable|numeric',
                'subtotalSinInt' => 'nullable|numeric',
                'comisionSinInt' => 'nullable|numeric',
                'cantSinNac' => 'nullable|numeric',
                'subtotalSinNac' => 'nullable|numeric',
                'comisionSinNac' => 'nullable|numeric',
                'cantAutor' => 'nullable|numeric',
                'subtotalAutor' => 'nullable|numeric',
                'comisionAutor' => 'nullable|numeric',
                'cantEditor' => 'nullable|numeric',
                'subtotalEditor' => 'nullable|numeric',
                'comisionEditor' => 'nullable|numeric',
                'cantWeb' => 'nullable|numeric',
                'subtotalWeb' => 'nullable|numeric',
                'comisionWeb' => 'nullable|numeric',
                'obsCientificos' => 'nullable|string',
                'obsDivulgacion' => 'nullable|string',
                'obsTraduccion' => 'nullable|string',
                'obsArbitrajeInt' => 'nullable|string',
                'obsArbitrajeNac' => 'nullable|string',
                'obsSinInt' => 'nullable|string',
                'obsSinNac' => 'nullable|string',
                'obsAutor' => 'nullable|string',
                'obsEditor' => 'nullable|string',
                'obsWeb' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
        ];
    }

    // Métodos alias para mantener compatibilidad con las rutas existentes
    public function storeform312(Request $request)
    {
        return $this->storeForm($request);
    }

    public function getFormData312(Request $request)
    {
        return $this->getFormData($request);
    }

    public function showForm312($teacherEmail = null)
    {
        return $this->showForm($teacherEmail);
    }

    public function updateform312(Request $request)
    {
        dd($request->all());

        return $this->updateForm($request);
    }

}

