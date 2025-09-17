<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\UsersResponseForm2_2;
use Illuminate\Http\Request;
use App\Models\DictaminatorsResponseForm2_2;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DictaminatorForm2_2Controller extends TransferController
{
    public function storeform22(Request $request)
    {
        try {
        $validatedData = $request->validate([
            'dictaminador_id' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'email' => 'required|exists:users,email',
            'hours' => 'required|numeric',
            'horasPosgrado' => 'required|numeric', // Allow nullable
            'horasSemestre' => 'required|numeric',
            'dse' => 'required|numeric',
            'dse2' => 'required|numeric',
            'comisionPosgrado' => 'required|numeric',
            'comisionLic' => 'required|numeric',
            'actv2Comision' => 'required|numeric',
            'obs2' => 'nullable|string',
            'obs2_2' => 'nullable|string',
            'user_type' => 'required|in:user,docente,dictaminator',
        ]);

            $validatedData['form_type'] = 'form2_2';

        if (!isset($validatedData['hours'])) {
            $validatedData['hours'] = 0;
        }
        $validatedData['obs2'] = $validatedData['obs2'] ?? 'sin comentarios';
        $validatedData['obs2_2'] = $validatedData['obs2_2'] ?? 'sin comentarios';

        try {
            $response = DictaminatorsResponseForm2_2::create($validatedData);
                // Actualizar automáticamente el modelo docente con la comision
                $this->updateUserResponseComision($validatedData['user_id'], $validatedData['actv2Comision']);
                DB::table('dictaminador_docente')->insert([
                    'user_id' => $validatedData['user_id'], // Asegúrate de que este ID exista
                    'dictaminador_id' => $response->dictaminador_id,
                    'form_type' => 'form2_2', // O el tipo de formulario correspondiente
                    'docente_email' => $response->email,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->checkAndTransfer('DictaminatorsResponseForm2_2');

                // DB::table('consolidated_responses')->insert([
                // 'user_id'=>$validatedData['user_id'], 
                // 'user_email'=>$validatedData['user_email'],
                // 'user_type'=>$validatedData['user_type'],
                // 'actv2Comision'=> $validatedData['actv2Comision'],]); 
                
                event(new EvaluationCompleted($validatedData['user_id']));
                
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage(),
            ], 500);
        }

         return response()->json([
                'success' => true,
                'message' => 'Data successfully saved',
                'data' => $validatedData
            ], 200);

            
    }catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getFormData22(Request $request)
    {
        try{
        $data = DictaminatorsResponseForm2_2::where('user_id', $request->query('user_id'))->first();
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

        }  catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving data: ' . $e->getMessage(),
            ], 500);
        }

    }

    private function updateUserResponseComision($userId, $comisionValue)
    {
        // Buscar el registro de UsersResponseForm2 correspondiente y actualizar comision1
        $userResponse = UsersResponseForm2_2::where('user_id', $userId)->first();

        if ($userResponse) {
            $userResponse->actv2Comision = $comisionValue;
            $userResponse->save();
        }
    }
}