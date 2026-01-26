<?php

namespace App\Http\Controllers;

use App\Models\UsersResponseForm2;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\DictaminatorsResponseForm2;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use App\Events\EvaluationCompleted;
use App\Models\UsersResponseForm1;
use App\Traits\ValidatesDictaminatorPeriod;
use Illuminate\Support\Facades\Log;

class DictaminatorForm2_Controller extends TransferController
{
    
    use ValidatesDictaminatorPeriod;

    public static function getValidationRules(): array
    {
        return [
                'dictaminador_id'=>'required|numeric',
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'horasActv2' => 'required|numeric',
                'puntajeEvaluar' => 'required|numeric', 
                'comision1' => 'required|numeric',
                'obs1' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
        ];
    }

    public function storeform2(Request $request)
    {
        try {
            // 1. Obtener el ID del dictaminador autenticado y añadirlo al request.
            $dictaminadorId = \Auth::id();
            $request->merge(['dictaminador_id' => $dictaminadorId]);

            // 2. Llamar a la validación de fecha al inicio del método
            if ($error = $this->validateEvaluationPeriod($request, 'form2')) {
                return $error;
            }

            //3. validad formulario unico
             $this->validarFormularioUnico($request, 'dictaminators_response_form2');

             //4. validaciones propias del formulario
            $validatedData = $request->validate(self::getValidationRules());

            // Actualizar el user_type del usuario si se proporciona
            $user = \App\Models\User::find($validatedData['user_id']);
            if ($user && isset($validatedData['user_type'])) {
                $user->user_type = $validatedData['user_type'];
                $user->save();
            }

            $validatedData['form_type'] = 'form2';
            
            // Default values for optional fields
            if (!isset($validatedData['puntajeEvaluar'])) {
                $validatedData['puntajeEvaluar'] = 0;
            }
            $validatedData['obs1'] = trim($validatedData['obs1']) !== '' ? $validatedData['obs1'] : 'sin comentarios';

            // Esto actualiza si existe o crea si no existe
            $response = DictaminatorsResponseForm2::updateOrCreate(
                [
                    'dictaminador_id' => $dictaminadorId,
                    'user_id' => $validatedData['user_id']
                ],
                $validatedData
            );

            // Actualizar automáticamente UsersResponseForm2 con comision1
            $this->updateUserResponseComision($validatedData['user_id'], $validatedData['comision1']);
            // Agregar a dictaminador_docente
            DB::table('dictaminador_docente')->updateOrInsert(
                [
                    'docente_id' => $validatedData['user_id'],
                    'dictaminador_id' => $response->dictaminador_id,
                    'form_type' => 'form2',
                ],
                [
                    'docente_email' => $response->email,
                    'updated_at' => now(),
            ]);
            // Llama a la verificación y transferencia
            $this->checkAndTransfer('DictaminatorsResponseForm2');

            // Disparar el evento después de guardar los datos
            event(new EvaluationCompleted($validatedData['user_id']));

            return response()->json([
                'success' => true,
                'message' => 'Formulario enviado',
                'data' => $validatedData
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation fallida',
                'errors' => $e->errors()
            ], 422);

        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar, formulario ya existente',
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
            \Log::info('DictaminatorForm2_Controller::getFormData2 - Inicio', ['request' => $request->all()]);

            $query = DictaminatorsResponseForm2::query();

            if ($request->has('user_id')) {
                $query->where('user_id', $request->query('user_id'));
            } elseif ($request->has('email')) {
                $query->where('email', $request->query('email'));
            }

            $dictaminadorId = $request->query('dictaminador_id');

            // 1. Intentar obtener el registro del dictaminador actual
            $data = $dictaminadorId ? (clone $query)->where('dictaminador_id', $dictaminadorId)->first() : null;

            // 2. Si no existe, buscar cualquier registro existente (de otro dictaminador)
            if (!$data) {
                $data = $query->first();
            }

            if (!$data) {
                \Log::info('DictaminatorForm2_Controller::getFormData2 - Data not found');
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found',
                    'form2' => [],
                ], 200);
            }

            \Log::info('DictaminatorForm2_Controller::getFormData2 - Data found', ['id' => $data->id]);

            return response()->json([
                'success' => true,
                'data' => $data,
                'form2' => [$data]
            ], 200);

        } catch (\Exception $e) {
            \Log::error('DictaminatorForm2_Controller::getFormData2 - Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving data: ' . $e->getMessage(),
            ], 800);
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

    // public function getDocentesByDictaminador(Request $request)
    // {
    //     $dictaminadorId = $request->input('dictaminador_id') ?? \Auth::id();
    //     $includeOthers = $request->input('include_others') === 'true';

    //     if ($includeOthers) {
    //         // Traer TODOS los docentes registrados en el sistema (tabla users)
    //         $docentes = \App\Models\User::where('user_type', 'docente')
    //             ->select('id', 'name', 'email')
    //             ->orderBy('name')
    //             ->get();
    //         return response()->json($docentes);
    //     }

    //     // Consultar la tabla pivote (historial de evaluaciones) solo para el dictaminador actual
    //     $query = DB::table('dictaminador_docente')
    //         ->join('users', 'dictaminador_docente.docente_id', '=', 'users.id')
    //         ->where('dictaminador_docente.dictaminador_id', $dictaminadorId)
    //         ->select('users.id', 'users.name', 'users.email')
    //         ->distinct();

    //     $docentes = $query->get();

    //     return response()->json($docentes);
    // }


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

    private function updateUserResponseComision($userId, $comisionValue)
    {
        // Buscar el registro de UsersResponseForm2 correspondiente y actualizar comision1
        $userResponse = UsersResponseForm2::where('user_id', $userId)->first();
 
        if ($userResponse) {
            $userResponse->comision1 = $comisionValue;
            $userResponse->save();
        }
    }
    
    public function showForm2(Request $request, $teacherEmail = null)
    {
        // Si se proporciona un email de docente en la URL, no necesitamos mostrar el buscador.
        // El script de autocompletado cargará los datos automáticamente.
        $emailFromUrl = $teacherEmail ?: $request->query('docente_email');
        $showSearchComponent = is_null($emailFromUrl);

        $hasData = false;
        if ($emailFromUrl) {
            $hasData = DictaminatorsResponseForm2::where('email', $emailFromUrl)->exists();
        }

        return view('form2', [
            'teacherEmailFromUrl' => $emailFromUrl,
            'showSearch' => $showSearchComponent,
            'hasData' => $hasData
        ]);
    }

         public function updateForm2(Request $request)
    {
        // Validar los datos de entrada
        $validatedData = $request->validate(self::getValidationRules());

        try {
            // Buscar el registro existente por user_id y dictaminador_id
            $response = DictaminatorsResponseForm2::updateOrCreate(
                [
                    'user_id' => $validatedData['user_id'],
                    'dictaminador_id' => $validatedData['dictaminador_id'],
                    'form_type' => $validatedData['form_type']
                ],
                $validatedData // Los datos con los que se actualizará o creará
            );

            return response()->json([
                'success' => true,
                'message' => 'Formulario actualizado correctamente.',
                'data' => $response
            ]);

        } catch (\Exception $e) {
            // Log del error para depuración
            \Log::error('Error al actualizar el formulario 2: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error en el servidor al actualizar.'
            ], 500);
        }
    }

}
