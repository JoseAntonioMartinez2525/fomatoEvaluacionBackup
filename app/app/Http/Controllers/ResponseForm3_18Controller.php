<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\UsersResponseForm3_18;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResponseForm3_18Controller extends Controller
{
    public function store318(Request $request)
    {
        try {
            // Validate request data
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_18' => 'required|numeric',
                //'comision3_18' => 'required|numeric',
                'cantComOrgInt' => 'required|numeric',
                'cantComOrgNac' => 'required|numeric',
                'cantComOrgReg' => 'required|numeric',
                'cantComApoyoInt' => 'required|numeric',
                'cantComApoyoNac' => 'required|numeric',
                'cantComApoyoReg' => 'required|numeric',
                'cantCicloComOrgInt' => 'required|numeric',
                'cantCicloComOrgNac' => 'required|numeric',
                'cantCicloComOrgReg' => 'required|numeric',
                'cantCicloComApoyoInt' => 'required|numeric',
                'cantCicloComApoyoNac' => 'required|numeric',
                'cantCicloComApoyoReg' => 'required|numeric',
                'subtotalComOrgInt' => 'required|numeric',
                'subtotalComOrgNac' => 'required|numeric',
                'subtotalComOrgReg' => 'required|numeric',
                'subtotalComApoyoInt' => 'required|numeric',
                'subtotalComApoyoNac' => 'required|numeric',
                'subtotalComApoyoReg' => 'required|numeric',
                'subtotalCicloComOrgInt' => 'required|numeric',
                'subtotalCicloComOrgNac' => 'required|numeric',
                'subtotalCicloComOrgReg' => 'required|numeric',
                'subtotalCicloComApoyoInt' => 'required|numeric',
                'subtotalCicloComApoyoNac' => 'required|numeric',
                'subtotalCicloComApoyoReg' => 'required|numeric',    
                            
                'obsComOrgInt' => 'nullable|string',
                'obsComOrgNac' => 'nullable|string',
                'obsComOrgReg' => 'nullable|string',
                'obsComApoyoInt' => 'nullable|string',
                'obsComApoyoNac' => 'nullable|string',
                'obsComApoyoReg' => 'nullable|string',
                'obsCicloComOrgInt' => 'nullable|string',
                'obsCicloComOrgNac' => 'nullable|string',
                'obsCicloComOrgReg' => 'nullable|string',
                'obsCicloComApoyoInt' => 'nullable|string',
                'obsCicloComApoyoNac' => 'nullable|string',
                'obsCicloComApoyoReg' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminador',



            ]);

            $validatedData['form_type'] = 'form3_18';

            // Assign default value if not provided
            $validatedData['obsComOrgInt'] = $validatedData['obsComOrgInt'] ?? 'sin comentarios';
            $validatedData['obsComOrgNac'] = $validatedData['obsComOrgNac'] ?? 'sin comentarios';
            $validatedData['obsComOrgReg'] = $validatedData['obsComOrgReg'] ?? 'sin comentarios';
            $validatedData['obsComApoyoInt'] = $validatedData['obsComApoyoInt'] ?? 'sin comentarios';
            $validatedData['obsComApoyoNac'] = $validatedData['obsComApoyoNac'] ?? 'sin comentarios';
            $validatedData['obsComApoyoReg'] = $validatedData['obsComApoyoReg'] ?? 'sin comentarios';
            $validatedData['obsCicloComOrgInt'] = $validatedData['obsCicloComOrgInt'] ?? 'sin comentarios';
            $validatedData['obsCicloComOrgNac'] = $validatedData['obsCicloComOrgNac'] ?? 'sin comentarios';
            $validatedData['obsCicloComOrgReg'] = $validatedData['obsCicloComOrgReg'] ?? 'sin comentarios';
            $validatedData['obsCicloComApoyoInt'] = $validatedData['obsCicloComApoyoInt'] ?? 'sin comentarios';
            $validatedData['obsCicloComApoyoNac'] = $validatedData['obsCicloComApoyoNac'] ?? 'sin comentarios';
            $validatedData['obsCicloComApoyoReg'] = $validatedData['obsCicloComApoyoReg'] ?? 'sin comentarios';


            // Consulta de datos con unión
            $docenteData = DB::table('dictaminators_response_form3_18')
                ->where('user_id', $validatedData['user_id'])
                ->select('comision3_18')
                ->first();


            // Pasar el valor a $validatedData para asegurar que esté disponible en la vista
            $validatedData['comision3_18'] = $docenteData->comision3_18 ?? null;

            // Create a new record using Eloquent ORM
            UsersResponseForm3_18::create($validatedData);
            // Disparar evento después de la creación del registro
            event(new EvaluationCompleted($validatedData['user_id']));

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

    public function getData318(Request $request)
    {
        try {
            $data = UsersResponseForm3_18::where('user_id', $request->query('user_id'))->first();
            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Error retrieving data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
