<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\UsersResponseForm3_13;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResponseForm3_13Controller extends Controller
{
    public function store313(Request $request)
    {
        try {
            // Validate request data
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'score3_13' => 'required|numeric',
                //'comision3_13' => 'required|numeric',
                'cantInicioFinanExt' => 'required|numeric',
                'subtotalInicioFinanExt' => 'required|numeric',
                'cantInicioInvInterno' => 'required|numeric',
                'subtotalInicioInvInterno' => 'required|numeric',
                'cantReporteFinanciamExt' => 'required|numeric',
                'subtotalReporteFinanciamExt' => 'required|numeric',
                'cantReporteInvInt' => 'required|numeric',
                'subtotalReporteInvInt' => 'required|numeric',
                'obsInicioFinancimientoExt' => 'nullable|string',
                'obsInicioInvInterno' => 'nullable|string',
                'obsReporteFinanciamExt' => 'nullable|string',
                'obsReporteInvInt' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',

            ]);

            $validatedData['form_type'] = 'form3_13';

            // Assign default value if not provided
            $validatedData['obsInicioFinancimientoExt'] = $validatedData['obsInicioFinancimientoExt'] ?? 'sin comentarios';
            $validatedData['obsInicioInvInterno'] = $validatedData['obsInicioInvInterno'] ?? 'sin comentarios';
            $validatedData['obsReporteFinanciamExt'] = $validatedData['obsReporteFinanciamExt'] ?? 'sin comentarios';
            $validatedData['obsReporteInvInt'] = $validatedData['obsReporteInvInt'] ?? 'sin comentarios';

            $docenteData = DB::table('dictaminators_response_form3_13')
                ->where('user_id', $validatedData['user_id'])
                ->select('comision3_13')
                ->first();


            // Pasar el valor a $validatedData para asegurar que esté disponible en la vista
            $validatedData['comision3_13'] = $docenteData->comision3_13 ?? null;
            // Create a new record using Eloquent ORM
            UsersResponseForm3_13::create($validatedData);
            
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

    public function getData313(Request $request)
    {
        try {
            $data = UsersResponseForm3_13::where('user_id', $request->query('user_id'))->first();
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
