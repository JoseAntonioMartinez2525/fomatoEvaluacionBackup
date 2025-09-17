<?php

namespace App\Http\Controllers;

use App\Models\UsersResponseForm2;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\DictaminatorsResponseForm2;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use App\Events\EvaluationCompleted;


class DictaminatorForm2_Controller extends TransferController
{
    public function storeform2(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'dictaminador_id'=>'required|numeric',
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'horasActv2' => 'required|numeric',
                'puntajeEvaluar' => 'required|numeric', 
                'comision1' => 'required|numeric',
                'obs1' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
                
            ]);

            $validatedData['form_type'] = 'form2';
            // Default values for optional fields
            if (!isset($validatedData['puntajeEvaluar'])) {
                $validatedData['puntajeEvaluar'] = 0;
            }
            if (!isset($validatedData['obs1'])) {
                $validatedData['obs1'] = "sin comentarios";
            }

            $response = DictaminatorsResponseForm2::create($validatedData);

            // Actualizar automáticamente UsersResponseForm2 con comision1
            $this->updateUserResponseComision($validatedData['user_id'], $validatedData['comision1']);
            // Agregar a dictaminador_docente
            DB::table('dictaminador_docente')->insert([
                'user_id' => $validatedData['user_id'], // Asegúrate de que este ID exista
                'dictaminador_id'=> $response->dictaminador_id,
                'form_type' => 'form2', // O el tipo de formulario correspondiente
                'docente_email'=>$response->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // Llama a la verificación y transferencia
            $this->checkAndTransfer('DictaminatorsResponseForm2');

            // Disparar el evento después de guardar los datos
            event(new EvaluationCompleted($validatedData['user_id']));

            return response()->json([
                'success' => true,
                'message' => 'Data successfully saved',
                'data' => $validatedData
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
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
            ], 500);
        }




    }

    public function getFormData2(Request $request)
    {
        try {
 
            // Check if dictaminador_id is present in the request
            if (!$request->has('dictaminador_id')) {
                return response()->json(['error' => 'dictaminador_id is required'], 400);
            }
            
            $data = DictaminatorsResponseForm2::where('dictaminador_id', $request->query('dictaminador_id'))->first();
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


    public function getConvocatoria($user_id)
    {
        // Obtener el registro de dictaminators_response_form2 por el dictaminador_id
        $dictaminatorResponse = DictaminatorsResponseForm2::where('user_id', $user_id)->first();

        if (!$dictaminatorResponse) {
            return response()->json(['error' => 'No se encontró la respuesta para el dictaminador'], 404);
        }

        // Obtener la convocatoria a través de la relación con UsersResponseForm1
        $convocatoria = $dictaminatorResponse->UsersResponseForm1->convocatoria ?? 'No hay convocatoria';

        // Devolver los datos en la respuesta o pasarlos a la vista
        return view('resumen_comision', compact('convocatoria'));
    }

    public function getDocentesByDictaminador(Request $request)
    {
        $dictaminador = DictaminatorsResponseForm2::find($request->dictaminador_id);

        if ($dictaminador) {
            $docentes = $dictaminador->docentes()->get();
            return response()->json($docentes);
        }

        return response()->json([], 404);  // Retorna un 404 si no se encuentra el dictaminador
    }


    public function asignarDocentes(Request $request, $dictaminador_id)
    {
        // Encuentra al dictaminador
        $dictaminator = DictaminatorsResponseForm2::find($dictaminador_id);

        // Verifica si el dictaminador existe
        if (!$dictaminator) {
            return response()->json(['success' => false, 'message' => 'Dictaminador no encontrado'], 404);
        }

        // Convertir los correos electrónicos en IDs
        $docenteEmails = $request->docentes; // Aquí obtienes los emails

        // Buscar los IDs de los docentes usando los correos electrónicos
        $docentes = UsersResponseForm2::whereIn('email', $docenteEmails)->get();

        foreach ($docentes as $docente) {
            // Asignar la relación y el correo electrónico
            $dictaminator->docentes()->attach($docente->user_id, ['docente_email' => $docente->email]);
        }

        return response()->json(['success' => true, 'message' => 'Docentes asignados correctamente']);
    }



    public function agregarDocente(Request $request, $dictaminador_id)
    {
        // Encuentra al dictaminador
        $dictaminator = DictaminatorsResponseForm2::find($dictaminador_id);

        // Verifica si el dictaminador existe
        if (!$dictaminator) {
            return response()->json(['success' => false, 'message' => 'Dictaminador no encontrado'], 404);
        }

        // Agregar un docente a la relación (esto agrega el docente sin eliminar los actuales)
        // $request->docente_id debe ser el ID del docente
        $dictaminator->docentes()->syncWithoutDetaching([$request->docente_id]);

        return response()->json(['success' => true, 'message' => 'Docente agregado correctamente']);
    }

    public function showForm()
    {
        $docentes = UsersResponseForm2::all(); // O cualquier lógica para obtener los docentes

        return view('form2', [
            
            'docentes' => $docentes,
            // Otras variables que necesites pasar a la vista
        ]);
    }

    private function updateUserResponseComision($userId, $comisionValue)
    {
        // Buscar el registro de UsersResponseForm2 correspondiente y actualizar comision1
        $userResponse = UsersResponseForm2::where('user_id', $userId)->first();

        if ($userResponse) {
            $userResponse->comision1 = $comisionValue;
            $userResponse->save();
        }
    }
    

}
