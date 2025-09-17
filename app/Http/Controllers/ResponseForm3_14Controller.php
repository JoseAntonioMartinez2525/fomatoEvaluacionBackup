<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\UsersResponseForm3_14;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResponseForm3_14Controller extends Controller
{
    public function store314(Request $request)
    {
        try {
            // Validate request data
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_14' => 'required|numeric',
                //'comision3_14' => 'required|numeric',
                'cantCongresoInt'=> 'required|numeric',
                'cantCongresoNac'=> 'required|numeric',
                'cantCongresoLoc'=> 'required|numeric',
                'subtotalCongresoInt'=> 'required|numeric',
                'subtotalCongresoNac'=> 'required|numeric',
                'subtotalCongresoLoc'=> 'required|numeric',
                'obsCongresoInt' => 'nullable|string',
                'obsCongresoNac' => 'nullable|string',
                'obsCongresoLoc' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',


            ]);

            $validatedData['form_type'] = 'form3_14';
            // Assign default value if not provided
            $validatedData['obsCongresoInt'] = $validatedData['obsCongresoInt'] ?? 'sin comentarios';
            $validatedData['obsCongresoNac'] = $validatedData['obsCongresoNac'] ?? 'sin comentarios';
            $validatedData['obsCongresoLoc'] = $validatedData['obsCongresoLoc'] ?? 'sin comentarios';


            $docenteData = DB::table('dictaminators_response_form3_14')
                ->where('user_id', $validatedData['user_id'])
                ->select('comision3_14')
                ->first();


            // Pasar el valor a $validatedData para asegurar que esté disponible en la vista
            $validatedData['comision3_14'] = $docenteData->comision3_14 ?? null;
            // Create a new record using Eloquent ORM
            UsersResponseForm3_14::create($validatedData);
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

    public function getData314(Request $request)
    {
        try {
            $data = UsersResponseForm3_14::where('user_id', $request->query('user_id'))->first();
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
