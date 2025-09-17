<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\ResponseInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

abstract class BaseResponse extends Model implements ResponseInterface
{
    // Implementa el método createResponse de la interfaz
    public static function createResponse(array $data)
    {
        // Validar y limpiar los datos según sea necesario
        $validatedData = self::validateData($data);

        // Crear y guardar el nuevo registro en la base de datos
        return self::create($validatedData);
    }


    /**
     * Método para validar y limpiar los datos de entrada
     *
     * @param array $data
     * @return array
     */
    private static function validateData(array $data)
    {

        // Define the options for "area" and "departamento"

        $areaOptions = ['Agropecuaria', 'Ciencias del Mar y Tierra', 'Ciencias Sociales y Humanidades', 'Otro'];
        $departamentoOptions = ['Agronomia', 'Ciencia animal y Conservación del habitat', 'Ciencias de la tierra', 'Ciencias Marinas y Costeras', 'Ciencias Sociales y Juridicas', 'Economia', 'Humanidades', 'Ingenieria en Pesquerias', 'Sistemas Computacionales'];

        //lógica de validación y limpieza de datos   
        $rules = [
            'user_id' => 'required|exists:users,id',
            'convocatoria' => 'string',
            'periodo' => 'string',
            'nombre' => 'string',
            'area' => 'required|in:' . implode(',', $areaOptions),
            'departamento' => 'required|in:' . implode(',', $departamentoOptions),
            'horasPosgrado' => 'nullable|integer',
            'horasSemestre' => 'nullable|integer',
            'elaboracion' => 'nullable|integer',
            'comisionIncisoA' => 'nullable|integer',
            'comisionIncisoB' => 'nullable|integer',
            'comisionIncisoC' => 'nullable|integer',
            'comisionIncisoD' => 'nullable|integer',
            'obs3_1_1' => 'nullable|string',
            'obs3_1_2' => 'nullable|string',
            'obs3_1_3' => 'nullable|string',
            'obs3_1_4' => 'nullable|string',
            'obs3_1_5' => 'nullable|string',
            'obs3_2_1' => 'nullable|string',
            'obs3_2_2' => 'nullable|string',
            'obs3_2_3' => 'nullable|string',
            'obs3_3_1' => 'nullable|string',
            'obs3_3_2' => 'nullable|string',
            'obs3_3_3' => 'nullable|string',
            'obs3_3_4' => 'nullable|string',
            'obs3_4_1' => 'nullable|string',
            'obs3_4_2' => 'nullable|string',
            'obs3_4_3' => 'nullable|string',
            'obs3_4_4' => 'nullable|string',
            'obs3_5_1' => 'nullable|string',
            'obs3_6' => 'nullable|string',
            'obs3_7' => 'nullable|string',
            'obs3_8' => 'nullable|string',
            'obs3_8_1' => 'nullable|string',
            'obs3_9_1' => 'nullable|string',
            'obs3_9_2' => 'nullable|string',
            'obs3_9_3' => 'nullable|string',
            'obs3_9_4' => 'nullable|string',
            'obs3_9_5' => 'nullable|string',
            'obs3_9_6' => 'nullable|string',
            'obs3_9_7' => 'nullable|string',
            'obs3_9_8' => 'nullable|string',
            'obs3_9_9' => 'nullable|string',
            'obs3_9_10' => 'nullable|string',
            'obs3_9_11' => 'nullable|string',
            'obs3_9_12' => 'nullable|string',
            'obs3_9_13' => 'nullable|string',
            'obs3_9_14' => 'nullable|string',
            'obs3_9_15' => 'nullable|string',
            'obs3_9_16' => 'nullable|string',
            'obs3_9_17' => 'nullable|string',
            'obsGrupal' => 'nullable|string',
            'obsIndividual' => 'nullable|string',
            'obsAsesoria' => 'nullable|string',
            'obsServicio' => 'nullable|string',
            'obsPracticas' => 'nullable|string',
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
            'obsInicioFinancimientoExt' => 'nullable|string',
            'obsInicioInvInterno' => 'nullable|string',
            'obsReporteFinanciamExt' => 'nullable|string',
            'obsReporteInvInt' => 'nullable|string',
            'obsCongresoInt' => 'nullable|string',
            'obsCongresoNac' => 'nullable|string',
            'obsCongresoLoc' => 'nullable|string',
            'obsPatentes' => 'nullable|string',
            'obsPrototipos' => 'nullable|string',
            'obsArbInt' => 'nullable|string',
            'obsArbNac' => 'nullable|string',
            'obsPubInt' => 'nullable|string',
            'obsPubNac' => 'nullable|string',
            'obsRevInt' => 'nullable|string',
            'obsRevNac' => 'nullable|string',
            'obsDifusionExt' => 'nullable|string',
            'obsDifusionInt' => 'nullable|string',
            'obsComOrgInt' => 'nullable|string',
            'obsComOrgNac' => 'nullable|string',
            'obsComOrgReg' => 'nullable|string',
            'obsComApoyoInt' => 'nullable|string',
            'obsComApoyoNac' => 'nullable|string',
            'obsComApoyoReg' => 'nullable|string',
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
            'comision1' => 'nullable|integer',
            'score3_1' => 'nullable|integer',
            'score3_2' => 'nullable|integer',
            'comision3_2' => 'nullable|integer',
            'score3_4' => 'nullable|integer',
            'comision3_4' => 'nullable|integer',
            'score3_5' => 'nullable|integer',
            'comision3_5' => 'nullable|integer',
            'score3_6' => 'nullable|integer',
            'comision3_6' => 'nullable|integer',
            'score3_8' => 'nullable|integer',
            'comision3_8' => 'nullable|integer',
            'score3_8_1' => 'nullable|integer',
            'comision3_8_1' => 'nullable|integer',
            'score3_9' => 'nullable|integer',
            'comision3_9' => 'nullable|integer',
            'score3_10' => 'nullable|integer',
            'comision3_10' => 'nullable|integer',
            'score3_11' => 'nullable|integer',
            'comision3_11' => 'nullable|integer',
            'score3_12' => 'nullable|integer',
            'comision3_12' => 'nullable|integer',
            'score3_13' => 'nullable|integer',
            'comision3_13' => 'nullable|integer',
            'score3_14' => 'nullable|integer',
            'comision3_14' => 'nullable|integer',
            'score3_15' => 'nullable|integer',
            'comision3_15' => 'nullable|integer',
            'score3_16' => 'nullable|integer',
            'comision3_16' => 'nullable|integer',
            'score3_17' => 'nullable|integer',
            'comision3_17' => 'nullable|integer',
            'score3_18' => 'nullable|integer',
            'comision3_18' => 'nullable|integer',
            'score3_19' => 'nullable|integer',
            'comision3_19' => 'nullable|integer',
            'docencia' => 'nullable|integer',

        ];
        // Perform validation
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            \Log::error('Validation failed:', $validator->errors()->all());
            throw new \Illuminate\Validation\ValidationException($validator);
        }
        // Logging the validated data
        \Log::info('Validated data:', $validator->validated());
        return $validator->validated();
    }
}