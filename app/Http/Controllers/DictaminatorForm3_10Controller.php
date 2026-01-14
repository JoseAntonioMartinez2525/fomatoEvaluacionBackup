<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\DictaminatorsResponseForm3_10;
use App\Models\UsersResponseForm3_10;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Traits\ValidatesDictaminatorPeriod;
class DictaminatorForm3_10Controller extends AbstractDictaminatorFormController
{
    /**
     * Devuelve el número del formulario
     */
    protected function getFormNumber(): string
    {
        return '3_10';
    }

    /**
     * Devuelve la clase del modelo de respuesta del dictaminador
     */
    protected function getDictaminatorModelClass(): string
    {
        return DictaminatorsResponseForm3_10::class;
    }

    /**
     * Devuelve la clase del modelo de respuesta del usuario
     */
    protected function getUserResponseModelClass(): string
    {
        return UsersResponseForm3_10::class;
    }

    /**
     * Devuelve los campos de observaciones
     */
    protected function getObservationFields(): array
    {
        return [
            'obsGrupal', 'obsIndividual', 
        ];
    }

    /**
     * Devuelve el nombre de la vista
     */
    protected function getViewName(): string
    {
        return 'form3_10';
    }

    /**
     * Devuelve las reglas de validación para el formulario 3.9.
     * @return array
     */
    public static function getValidationRules(): array
    {
        return [
                'dictaminador_id' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_10' => 'required|numeric',
                'comision3_10' => 'required|numeric',
                'comisionGrupal' => 'nullable|numeric',
                'comisionIndividual' => 'nullable|numeric',
                'grupalesCant' => 'nullable|numeric',
                'evaluarGrupales' => 'nullable|numeric',
                'evaluarIndividual' => 'nullable|numeric',
                'individualCant' => 'nullable|numeric',
                'obsGrupal' => 'nullable|string',
                'obsIndividual' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
        ];
    }

    // Métodos alias para mantener compatibilidad con las rutas existentes
    public function storeform310(Request $request)
    {
        return $this->storeForm($request);
    }

    public function getFormData310(Request $request)
    {
        return $this->getFormData($request);
    }

    public function showForm310($teacherEmail = null)
    {

        return $this->showForm($teacherEmail);
    }

    public function updateform310(Request $request)
    {
        return $this->updateForm($request);
    }

}


