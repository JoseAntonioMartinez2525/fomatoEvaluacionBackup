<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use Illuminate\Http\Request;
use App\Models\UsersResponseForm3_4; 
use Illuminate\Support\Facades\DB;// Adjust the model namespace as needed

class ResponseForm3_4Controller extends Controller
{
    public function store34(Request $request)
    {
        try {
            // Validate request data
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_4' => 'required|numeric',
                'cantInternacional' => 'required|numeric',
                'cantNacional' => 'required|numeric',
                'cantidadRegional' => 'required|numeric',
                'cantPreparacion' => 'required|numeric',
                'cantInternacional2' => 'required|numeric',
                'cantNacional2' => 'required|numeric',
                'cantidadRegional2' => 'required|numeric',
                'cantPreparacion2' => 'required|numeric',
                'obs3_4_1' => 'nullable|string',
                'obs3_4_2' => 'nullable|string',
                'obs3_4_3' => 'nullable|string',
                'obs3_4_4' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
            ]);

            $validatedData['form_type'] = 'form3_4';

            // Assign default value if not provided
            $validatedData['obs3_4_1'] = $validatedData['obs3_4_1'] ?? 'sin comentarios';
            $validatedData['obs3_4_2'] = $validatedData['obs3_4_2'] ?? 'sin comentarios';
            $validatedData['obs3_4_3'] = $validatedData['obs3_4_3'] ?? 'sin comentarios';
            $validatedData['obs3_4_4'] = $validatedData['obs3_4_4'] ?? 'sin comentarios';

            $docenteData = DB::table('dictaminators_response_form3_4')
                ->where('user_id', $validatedData['user_id'])
                ->select('comision3_4')
                ->first();


            // Pasar el valor a $validatedData para asegurar que esté disponible en la vista
            $validatedData['comision3_4'] = $docenteData->comision3_4 ?? null;

            // Create a new record using Eloquent ORM
            UsersResponseForm3_4::create($validatedData);
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

    public function getData34(Request $request)
    {
        try {
            $data = UsersResponseForm3_4::where('user_id', $request->query('user_id'))->first();
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
