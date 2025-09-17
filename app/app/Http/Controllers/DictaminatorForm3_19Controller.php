<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\DictaminatorsResponseForm3_19;
use App\Models\UsersResponseForm3_19;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
class DictaminatorForm3_19Controller extends TransferController
{
    public function storeform319(Request $request)
    {

        try {
            $validatedData = $request->validate([
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
            ]);

            if (!isset($validatedData['score3_19'])) {
                $validatedData['score3_19'] = 0;
            }
            $validatedData['obsCGUtitular'] = $validatedData['obsCGUtitular'] ?? 'sin comentarios';
            $validatedData['obsCGUespecial'] = $validatedData['obsCGUespecial'] ?? 'sin comentarios';
            $validatedData['obsCGUpermanente'] = $validatedData['obsCGUpermanente'] ?? 'sin comentarios';
            $validatedData['obsCAACtitular'] = $validatedData['obsCAACtitular'] ?? 'sin comentarios';
            $validatedData['obsCAACintegCom'] = $validatedData['obsCAACintegCom'] ?? 'sin comentarios';
            $validatedData['obsComDepart'] = $validatedData['obsComDepart'] ?? 'sin comentarios';
            $validatedData['obsComPEDPD'] = $validatedData['obsComPEDPD'] ?? 'sin comentarios';
            $validatedData['obsComPartPos'] = $validatedData['obsComPartPos'] ?? 'sin comentarios';
            $validatedData['obsRespPos'] = $validatedData['obsRespPos'] ?? 'sin comentarios';
            $validatedData['obsRespCarrera'] = $validatedData['obsRespCarrera'] ?? 'sin comentarios';
            $validatedData['obsRespProd'] = $validatedData['obsRespProd'] ?? 'sin comentarios';
            $validatedData['obsRespLab'] = $validatedData['obsRespLab'] ?? 'sin comentarios';
            $validatedData['obsExamProf'] = $validatedData['obsExamProf'] ?? 'sin comentarios';
            $validatedData['obsExamAcademicos'] = $validatedData['obsExamAcademicos'] ?? 'sin comentarios';
            $validatedData['obsPRODEPformResp'] = $validatedData['obsPRODEPformResp'] ?? 'sin comentarios';
            $validatedData['obsPRODEPformInteg'] = $validatedData['obsPRODEPformInteg'] ?? 'sin comentarios';
            $validatedData['obsPRODEPenconsResp'] = $validatedData['obsPRODEPenconsResp'] ?? 'sin comentarios';
            $validatedData['obsPRODEPenconsInteg'] = $validatedData['obsPRODEPenconsInteg'] ?? 'sin comentarios';
            $validatedData['obsPRODEPconsResp'] = $validatedData['obsPRODEPconsResp'] ?? 'sin comentarios';
            $validatedData['obsPRODEPconsInteg'] = $validatedData['obsPRODEPconsInteg'] ?? 'sin comentarios';

            $validatedData['form_type'] = 'form3_19';

            $response = DictaminatorsResponseForm3_19::create($validatedData);
            
            // Actualizar automáticamente el modelo docente con la comision
            $this->updateUserResponseComision($validatedData['user_id'], $validatedData['comision3_19']);
            DB::table('dictaminador_docente')->insert([
                //'dictaminador_form_id' => $response->id, // Asegúrate de que este ID exista
                'user_id' => $validatedData['user_id'], // Asegúrate de que este ID exista
                'dictaminador_id' => $response->dictaminador_id,
                'form_type' => 'form3_19', // O el tipo de formulario correspondiente
                'docente_email' => $response->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->checkAndTransfer('DictaminatorsResponseForm3_19');

            event(new EvaluationCompleted($validatedData['user_id']));
            
            return response()->json([
                'success' => true,
                'message' => 'Data successfully saved',
                'data' => $validatedData,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
            ], 500); // Cambiado de 1200 a 500
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
            ], 500); // Cambiado de 1200 a 500
        }

    }

    public function getFormData319(Request $request)
    {
        try {
            $data = DictaminatorsResponseForm3_19::where('user_id', $request->query('user_id'))->first();
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving data: ' . $e->getMessage(),
            ], 500);
        }

    }

    private function updateUserResponseComision($userId, $comisionValue)
    {
        // Buscar el registro de UsersResponseForm2 correspondiente y actualizar comision1
        $userResponse = UsersResponseForm3_19::where('user_id', $userId)->first();

        if ($userResponse) {
            $userResponse->comision3_19 = $comisionValue;
            $userResponse->save();
        }
    }
}

