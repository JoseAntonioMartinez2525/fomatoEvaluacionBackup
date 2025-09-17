<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\UsersResponseForm3_16;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResponseForm3_16Controller extends Controller
{
    public function store316(Request $request)
    {
        try {
            // Validate request data
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_16' => 'required|numeric',
                //'comision3_16' => 'required|numeric',
                'cantArbInt' => 'required|numeric',
                'cantArbNac' => 'required|numeric',
                'cantPubInt' => 'required|numeric',
                'cantPubNac' => 'required|numeric',
                'cantRevInt' => 'required|numeric',
                'cantRevNac' => 'required|numeric',
                'cantRevista' => 'required|numeric',
                'subtotalArbInt' => 'required|numeric',
                'subtotalArbNac' => 'required|numeric',
                'subtotalPubInt' => 'required|numeric',
                'subtotalPubNac' => 'required|numeric',
                'subtotalRevInt' => 'required|numeric',
                'subtotalRevNac' => 'required|numeric',
                'subtotalRevista' => 'required|numeric',
                'obsArbInt' => 'nullable|string',
                'obsArbNac' => 'nullable|string',
                'obsPubInt' => 'nullable|string',
                'obsPubNac' => 'nullable|string',
                'obsRevInt' => 'nullable|string',
                'obsRevNac' => 'nullable|string',
                'obsRevista' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
 
            ]);

            $validatedData['form_type'] = 'form3_16';

            // Assign default value if not provided
            $validatedData['obsArbInt'] = $validatedData['obsArbInt'] ?? 'sin comentarios';
            $validatedData['obsArbNac'] = $validatedData['obsArbNac'] ?? 'sin comentarios';
            $validatedData['obsPubInt'] = $validatedData['obsPubInt'] ?? 'sin comentarios';
            $validatedData['obsPubNac'] = $validatedData['obsPubNac'] ?? 'sin comentarios';
            $validatedData['obsRevInt'] = $validatedData['obsRevInt'] ?? 'sin comentarios';
            $validatedData['obsRevNac'] = $validatedData['obsRevNac'] ?? 'sin comentarios';
            $validatedData['obsRevista'] = $validatedData['obsRevista'] ?? 'sin comentarios';

            $docenteData = DB::table('dictaminators_response_form3_16')
                ->where('user_id', $validatedData['user_id'])
                ->select('comision3_16')
                ->first();


            // Pasar el valor a $validatedData para asegurar que esté disponible en la vista
            $validatedData['comision3_16'] = $docenteData->comision3_16 ?? null;

            // Create a new record using Eloquent ORM
            UsersResponseForm3_16::create($validatedData);
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

    public function getData316(Request $request)
    {
        try {
            $data = UsersResponseForm3_16::where('user_id', $request->query('user_id'))->first();
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

