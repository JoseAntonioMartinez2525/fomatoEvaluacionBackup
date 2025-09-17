<?php

namespace App\Http\Controllers;

use App\Models\UserResume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class ResumeController extends Controller
{
    public function storeResume(Request $request)
    {
        //dd($request->input('user_type'));

        try{$validatedData = $request->validate([
            'dictaminador_id' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'email' => 'required|exists:users,email',
            'comision_actividad_1_total' => 'required|numeric',
            'comision_actividad_2_total' => 'required|numeric',
            'comision_actividad_3_total' => 'required|numeric',
            'total_puntaje'=> 'required|numeric',
            'minima_calidad'=>'required|string',
            'minima_total'=>'required|string',
                'user_type' => ['required', 'in:user,docente,dictaminador', 'lowercase'],

        ]);



         // Create a new record using Eloquent ORM
            $response = UserResume::create($validatedData);
            DB::table('dictaminador_docente')->insert([
                'user_id' => $validatedData['user_id'], // Asegúrate de que este ID exista
                'dictaminador_id' => $response->dictaminador_id,
                'form_type' => 'form4', // O el tipo de formulario correspondiente
                'docente_email' => $response->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->checkAndTransfer('UserResume');
            
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

    public function getDataResume(Request $request)
    {
        try {
            $data = UserResume::where('dictaminador_id', $request->query('dictaminador_id'))->first();
            $dataUser_id = UserResume::where('user_id', $request->query('user_id'))->first();
            if (!$data || !$dataUser_id) {
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
}
