<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\UsersResponseForm3_19;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ResponseForm3_19Controller extends Controller
{
    public function store319(Request $request)
    {
        try {
            // Validate request data
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_19' => 'required|numeric',
                //'comision3_19' => 'required|numeric',
                'cantCGUtitular'=> 'required|numeric',
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
                'user_type' => 'required|in:user,docente,dictaminador',



            ]);

            $validatedData['form_type'] = 'form3_19';

            // Assign default value if not provided
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


            $docenteData = DB::table('dictaminators_response_form3_19')
                ->where('user_id', $validatedData['user_id'])
                ->select('comision3_19')
                ->first();


            // Pasar el valor a $validatedData para asegurar que esté disponible en la vista
            $validatedData['comision3_19'] = $docenteData->comision3_19 ?? null;

            // Create a new record using Eloquent ORM
            UsersResponseForm3_19::create($validatedData);
            // Disparar evento después de la creación del registro
            event(new EvaluationCompleted($validatedData['user_id']));

            $consolidatedData = DB::table('consolidated_responses')
                ->where('user_id', $validatedData['user_id'])
                ->first();

            if ($consolidatedData) {
                // Actualiza el registro del docente con la comision1
                UsersResponseForm3_19::where('user_id', $validatedData['user_id'])
                    ->update(['comision3_19' => $consolidatedData->comision3_19]);
            }
            return response()->json([
                'success' => true,
                'message' => 'Form submitted successfully!',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors
            \Log::error('Validation error: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Log other errors
            \Log::error('Error submitting form: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error submitting form: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getData319(Request $request)
    {
        try {
            $data = UsersResponseForm3_19::where('user_id', $request->query('user_id'))->first();
            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Error retrieving data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function generatePdf(Request $request)
    {
        $userType = Auth::user()->user_type;

        if ($userType !== 'docente') {
            // Fetch the user data based on the email from the request
            $email = $request->input('email');
            $userData = UsersResponseForm3_19::where('email', $email)->first();

            if (!$userData) {
                return response()->json(['error' => 'User data not found'], 404);
            }


        //Return the user data to the frontend
        return response()->json(['data' => $userData]);
        }

        return redirect()->back()->with('error', 'PDF generation is not allowed for this user type.');
    }
}
