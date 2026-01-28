<?php

namespace App\Http\Controllers;

use App\Models\DictaminatorsResponseForm3_13;
use App\Models\UsersResponseForm3_13;

use Illuminate\Http\Request;


class DictaminatorForm3_13Controller extends AbstractDictaminatorFormController
{
    /**
     * Devuelve el número del formulario
     */
    protected function getFormNumber(): string
    {
        return '3_13';
    }

    /**
     * Devuelve la clase del modelo de respuesta del dictaminador
     */
    protected function getDictaminatorModelClass(): string
    {
        return DictaminatorsResponseForm3_13::class;
    }

    /**
     * Devuelve la clase del modelo de respuesta del usuario
     */
    protected function getUserResponseModelClass(): string
    {
        return UsersResponseForm3_13::class;
    }

    /**
     * Devuelve los campos de observaciones
     */
    protected function getObservationFields(): array
    {
        return [
            'obsInicioFinancimientoExt', 'obsInicioInvInterno', 'obsReporteFinanciamExt', 'obsReporteInvInt'
        ];
    }

    /**
     * Devuelve el nombre de la vista
     */
    protected function getViewName(): string
    {
        return 'form3_13';
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
                'score3_13' => 'required|numeric',
                'comision3_13' => 'required|numeric',
                'cantInicioFinanExt' => 'nullable|numeric',
                'subtotalInicioFinanExt' => 'nullable|numeric',
                'comisionInicioFinancimientoExt' => 'nullable|numeric',
                'cantInicioInvInterno' => 'nullable|numeric',
                'subtotalInicioInvInterno' => 'nullable|numeric',
                'comisionInicioInvInterno' => 'nullable|numeric',
                'cantReporteFinanciamExt' => 'nullable|numeric',
                'subtotalReporteFinanciamExt' => 'nullable|numeric',
                'comisionReporteFinanciamExt' => 'nullable|numeric',
                'cantReporteInvInt' => 'nullable|numeric',
                'subtotalReporteInvInt' => 'nullable|numeric',
                'comisionReporteInvInt' => 'nullable|numeric',
                'obsInicioFinancimientoExt' => 'nullable|string',
                'obsInicioInvInterno' => 'nullable|string',
                'obsReporteFinanciamExt' => 'nullable|string',
                'obsReporteInvInt' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
        ];
    }

    // Métodos alias para mantener compatibilidad con las rutas existentes
    public function storeform313(Request $request)
    {
        return $this->storeForm($request);
    }

    public function getFormData313(Request $request)
    {
        return $this->getFormData($request);
    }

    public function showForm313($teacherEmail = null)
    {
        return $this->showForm($teacherEmail);
    }

    public function updateform313(Request $request)
    {
        
        return $this->updateForm($request);
    }

}
