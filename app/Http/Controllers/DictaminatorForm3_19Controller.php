<?php

namespace App\Http\Controllers;

use App\Models\DictaminatorsResponseForm3_19;
use App\Models\UsersResponseForm3_19;

use Illuminate\Http\Request;

class DictaminatorForm3_19Controller extends AbstractDictaminatorFormController
{
    /**
     * Devuelve el número del formulario
     */
    protected function getFormNumber(): string
    {
        return '3_19';
    }

    /**
     * Devuelve la clase del modelo de respuesta del dictaminador
     */
    protected function getDictaminatorModelClass(): string
    {
        return DictaminatorsResponseForm3_19::class;
    }

    /**
     * Devuelve la clase del modelo de respuesta del usuario
     */
    protected function getUserResponseModelClass(): string
    {
        return UsersResponseForm3_19::class;
    }

    /**
     * Devuelve los campos de observaciones
     */
    protected function getObservationFields(): array
    {
        return [
            'obsCGUtitular', 'obsCGUespecial', 'obsCGUpermanente', 'obsCAACtitular', 'obsCAACintegCom', 'obsComDepart', 'obsComPEDPD', 'obsComPartPos', 'obsRespPos', 'obsRespCarrera', 'obsRespProd', 'obsRespLab', 'obsExamProf', 'obsExamAcademicos', 'obsPRODEPformResp', 'obsPRODEPformInteg', 'obsPRODEPenconsResp', 'obsPRODEPenconsInteg', 'obsPRODEPconsResp', 'obsPRODEPconsInteg',
        ];
    }

    /**
     * Devuelve el nombre de la vista
     */
    protected function getViewName(): string
    {
        return 'form3_19';
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
                'score3_19' => 'required|numeric',
                'comision3_19' => 'required|numeric',
                'cantCGUtitular' => 'required|numeric',
                'subtotalCGUtitular' => 'required|numeric',
                'cantCGUespecial' => 'required|numeric',
                'subtotalCGUespecial' => 'required|numeric',
                'cantCGUpermanente' => 'required|numeric',
                'subtotalCGUpermanente' => 'required|numeric',
                'cantCAACtitular' => 'required|numeric',
                'subtotalCAACtitular' => 'required|numeric',
                'cantCAACintegCom' => 'required|numeric',
                'subtotalCAACintegCom' => 'required|numeric',
                'cantComDepart' => 'required|numeric',
                'subtotalComDepart' => 'required|numeric',
                'cantComPEDPD' => 'required|numeric',
                'subtotalComPEDPD' => 'required|numeric',
                'cantComPartPos' => 'required|numeric',
                'subtotalComPartPos' => 'required|numeric',
                'cantRespPos' => 'required|numeric',
                'subtotalRespPos' => 'required|numeric',
                'cantRespCarrera' => 'required|numeric',
                'subtotalRespCarrera' => 'required|numeric',
                'cantRespProd' => 'required|numeric',
                'subtotalRespProd' => 'required|numeric',
                'cantRespLab' => 'required|numeric',
                'subtotalRespLab' => 'required|numeric',
                'cantExamProf' => 'required|numeric',
                'subtotalExamProf' => 'required|numeric',
                'cantExamAcademicos' => 'required|numeric',
                'subtotalExamAcademicos' => 'required|numeric',
                'cantPRODEPformResp' => 'required|numeric',
                'subtotalPRODEPformResp' => 'required|numeric',
                'cantPRODEPformInteg' => 'required|numeric',
                'subtotalPRODEPformInteg' => 'required|numeric',
                'cantPRODEPenconsResp' => 'required|numeric',
                'subtotalPRODEPenconsResp' => 'required|numeric',
                'cantPRODEPenconsInteg' => 'required|numeric',
                'subtotalPRODEPenconsInteg' => 'required|numeric',
                'cantPRODEPconsResp' => 'required|numeric',
                'subtotalPRODEPconsResp' => 'required|numeric',
                'cantPRODEPconsInteg' => 'required|numeric',
                'subtotalPRODEPconsInteg' => 'required|numeric',
                'comCGUtitular' => 'required|numeric',
                'comCGUespecial' => 'required|numeric',
                'comCGUpermanente' => 'required|numeric',
                'comCAACtitular' => 'required|numeric',
                'comCAACintegCom' => 'required|numeric',
                'comComDepart' => 'required|numeric',
                'comComPEDPD' => 'required|numeric',
                'comComPartPos' => 'required|numeric',
                'comRespPos' => 'required|numeric',
                'comRespCarrera' => 'required|numeric',
                'comRespProd' => 'required|numeric',
                'comRespLab' => 'required|numeric',
                'comExamProf' => 'required|numeric',
                'comExamAcademicos' => 'required|numeric',
                'comPRODEPformResp' => 'required|numeric',
                'comPRODEPformInteg' => 'required|numeric',
                'comPRODEPenconsResp' => 'required|numeric',
                'comPRODEPenconsInteg' => 'required|numeric',
                'comPRODEPconsResp' => 'required|numeric',
                'comPRODEPconsInteg' => 'required|numeric',
                'obsCGUtitular' => 'nullable|string',
                'obsCGUespecial' => 'nullable|string',
                'obsCGUpermanente' => 'nullable|string',
                'obsCAACtitular' => 'nullable|string',
                'obsCAACintegCom' => 'nullable|string',
                'obsComDepart' => 'nullable|string',
                'obsComPEDPD' => 'nullable|string',
                'obsComPartPos' => 'nullable|string',
                'obsRespPos' => 'nullable|string',
                'obsRespCarrera' => 'nullable|string',
                'obsRespProd' => 'nullable|string',
                'obsRespLab' => 'nullable|string',
                'obsExamProf' => 'nullable|string',
                'obsExamAcademicos' => 'nullable|string',
                'obsPRODEPformResp' => 'nullable|string',
                'obsPRODEPformInteg' => 'nullable|string',
                'obsPRODEPenconsResp' => 'nullable|string',
                'obsPRODEPenconsInteg' => 'nullable|string',
                'obsPRODEPconsResp' => 'nullable|string',
                'obsPRODEPconsInteg' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
        ];
    }

    // Métodos alias para mantener compatibilidad con las rutas existentes
    public function storeform319(Request $request)
    {
        return $this->storeForm($request);
    }

    public function getFormData319(Request $request)
    {
        return $this->getFormData($request);
    }

    public function showForm319($teacherEmail = null)
    {
        return $this->showForm($teacherEmail);
    }

    public function updateform319(Request $request)
    {
        
        return $this->updateForm($request);
    }

}
