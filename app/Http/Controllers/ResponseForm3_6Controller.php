<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use Illuminate\Http\Request;
use App\Models\UsersResponseForm3_6;
use Illuminate\Support\Facades\DB;
class ResponseForm3_6Controller extends Controller
{
    public function store36(Request $request)
    {
        try {
            // Validate request data
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_6' => 'required|numeric',
                //'comision3_6' => 'required|numeric',
                'puntaje3_6' => 'required|numeric',
                'puntajeHoras3_6' => 'required|numeric',
                'obs3_6_1' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',

            ]);

            $validatedData['form_type'] = 'form3_6';
            // Assign default value if not provided
            $validatedData['obs3_6_1'] = $validatedData['obs3_6_1'] ?? 'sin comentarios';

            // Consulta de datos con unión
            $docenteData = DB::table('dictaminators_response_form3_6')
                ->where('user_id', $validatedData['user_id'])
                ->select('comision3_6')
                ->first();


            // Pasar el valor a $validatedData para asegurar que esté disponible en la vista
            $validatedData['comision3_6'] = $docenteData->comision3_6 ?? null;

            // Create a new record using Eloquent ORM
            UsersResponseForm3_6::create($validatedData);
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

    public function getData36(Request $request)
    {
        try {
            $data = UsersResponseForm3_6::where('user_id', $request->query('user_id'))->first();
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
