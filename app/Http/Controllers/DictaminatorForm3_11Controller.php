<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\DictaminatorsResponseForm3_11;
use App\Models\UsersResponseForm3_11;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Traits\ValidatesDictaminatorPeriod;

class DictaminatorForm3_11Controller extends AbstractDictaminatorFormController
{
    /**
     * Devuelve el número del formulario
     */
    protected function getFormNumber(): string
    {
        return '3_11';
    }

    /**
     * Devuelve la clase del modelo de respuesta del dictaminador
     */
    protected function getDictaminatorModelClass(): string
    {
        return DictaminatorsResponseForm3_11::class;
    }

    /**
     * Devuelve la clase del modelo de respuesta del usuario
     */
    protected function getUserResponseModelClass(): string
    {
        return UsersResponseForm3_11::class;
    }

    /**
     * Devuelve los campos de observaciones
     */
    protected function getObservationFields(): array
    {
        return [
            'obsAsesoria', 'obsServicio', 'obsPracticas',
        ];
    }

    /**
     * Devuelve el nombre de la vista
     */
    protected function getViewName(): string
    {
        return 'form3_11';
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
            'score3_11' => 'required|numeric',
            'comision3_11' => 'required|numeric',
            'cantAsesoria' => 'nullable|numeric',
            'cantServicio' => 'nullable|numeric',
            'cantPracticas' => 'nullable|numeric',
            'subtotalAsesoria' => 'nullable|numeric',
            'subtotalServicio' => 'nullable|numeric',
            'subtotalPracticas' => 'nullable|numeric',
            'comisionAsesoria' => 'nullable|numeric',
            'comisionServicio' => 'nullable|numeric',
            'comisionPracticas' => 'nullable|numeric',
            'obsAsesoria' => 'nullable|string',
            'obsServicio' => 'nullable|string',
            'obsPracticas' => 'nullable|string',
            'user_type' => 'required|in:user,docente,dictaminator',
        ];
    }

    // Métodos alias para mantener compatibilidad con las rutas existentes
    public function storeform311(Request $request)
    {
        return $this->storeForm($request);
    }

    public function getFormData311(Request $request)
    {
        return $this->getFormData($request);
    }

    public function showForm311($teacherEmail = null)
    {
        return $this->showForm($teacherEmail);
    }

    public function updateform311(Request $request)
    {
        return $this->updateForm($request);
    }
}

