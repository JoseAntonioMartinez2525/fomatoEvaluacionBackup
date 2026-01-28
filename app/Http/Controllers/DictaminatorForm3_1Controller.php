<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ResponseJson;
use App\Events\EvaluationCompleted;
use App\Models\DictaminatorsResponseForm3_1;
use App\Models\UsersResponseForm3_1;
use App\Models\DictaminatorsResponseForm3_2;
use App\Models\UsersResponseForm3_10;
use App\Models\UsersResponseForm3_11;
use App\Models\UsersResponseForm3_12;
use App\Models\UsersResponseForm3_13;
use App\Models\UsersResponseForm3_14;
use App\Models\UsersResponseForm3_15;
use App\Models\UsersResponseForm3_16;
use App\Models\UsersResponseForm3_17;
use App\Models\UsersResponseForm3_18;
use App\Models\UsersResponseForm3_19;
use App\Models\UsersResponseForm3_2;
use App\Models\UsersResponseForm3_3;
use App\Models\UsersResponseForm3_4;
use App\Models\UsersResponseForm3_5;
use App\Models\UsersResponseForm3_6;
use App\Models\UsersResponseForm3_7;
use App\Models\UsersResponseForm3_8;
use App\Models\UsersResponseForm3_8_1;
use App\Models\UsersResponseForm3_9;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Traits\ValidatesDictaminatorPeriod;
use Illuminate\Support\Facades\Auth;

class DictaminatorForm3_1Controller extends TransferController
{
    use ValidatesDictaminatorPeriod;

    /**
     * Devuelve las reglas de validación para el formulario 3.1.
     * @return array
     */
    public static function getValidationRules(): array
    {
        return [
            'dictaminador_id' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'email' => 'required|email', // 'exists:users,email' se puede omitir si ya validamos user_id
            'elaboracion' => 'required|numeric',
            'elaboracionSubTotal1' => 'required|numeric',
            'comisionIncisoA' => 'required|numeric',
            'elaboracion2' => 'required|numeric',
            'elaboracionSubTotal2' => 'required|numeric',
            'comisionIncisoB' => 'required|numeric',
            'elaboracion3' => 'required|numeric',
            'elaboracionSubTotal3' => 'required|numeric',
            'comisionIncisoC' => 'required|numeric',
            'elaboracion4' => 'required|numeric',
            'elaboracionSubTotal4' => 'required|numeric',
            'comisionIncisoD' => 'required|numeric',
            'elaboracion5' => 'required|numeric',
            'elaboracionSubTotal5' => 'required|numeric',
            'comisionIncisoE' => 'required|numeric',
            'score3_1' => 'required|numeric',
            'actv3Comision' => 'required|numeric',
            'obs3_1_1' => 'nullable|string', 'obs3_1_2' => 'nullable|string', 'obs3_1_3' => 'nullable|string', 'obs3_1_4' => 'nullable|string', 'obs3_1_5' => 'nullable|string',
            'user_type' => 'sometimes|in:user,docente,dictaminator',
        ];
    }

    public function storeform31(Request $request)
    {
        
        
        try {
            // 1. Obtener el ID del dictaminador autenticado y añadirlo al request.
            $dictaminadorId = \Auth::id();
            $request->merge(['dictaminador_id' => $dictaminadorId]);

            // 2. Llamar a la validación de fecha al inicio del método
            if ($error = $this->validateEvaluationPeriod($request, 'form3_1')) {
                return $error;
            }

            \Log::info('Inicio de storeform31');

            //3. validad formulario unico
             $this->validarFormularioUnico($request, 'dictaminators_response_form3_1');

            $validatedData = $request->validate(self::getValidationRules());

            // Actualizar el user_type del usuario si se proporciona
            $user = \App\Models\User::find($validatedData['user_id']);
            if ($user && isset($validatedData['user_type'])) {
                $user->user_type = $validatedData['user_type'];
                $user->save();
            }

            \Log::info('Datos validados:', $validatedData);

            $validatedData['form_type'] = 'form3_1';

            
            if (!isset($validatedData['score3_1'])) {
                $validatedData['score3_1'] = 0;
            }
            
            $campos = ['obs3_1_1', 'obs3_1_2', 'obs3_1_3', 'obs3_1_4', 'obs3_1_5'];

            foreach ($campos as $campo) {
                $validatedData[$campo] = trim($validatedData[$campo] ?? '') !== '' ? $validatedData[$campo] : 'sin comentarios';

            }


            
            $response = DictaminatorsResponseForm3_1::updateOrCreate(
                [
                    'dictaminador_id' => $dictaminadorId,
                    'user_id' => $validatedData['user_id']
                ],
                $validatedData
            );

            $this->updateUserResponseComision($validatedData['user_id'], $validatedData['actv3Comision']);

             \Log::info('updateUserResponseComision ejecutado');

            \Log::info('Datos guardados en DictaminatorsResponseForm3_1:', $response->toArray());

            // Usar updateOrInsert para evitar errores de duplicados si el registro ya existe.
            // Esto es útil si un dictaminador re-evalúa a un docente.
            DB::table('dictaminador_docente')->updateOrInsert(
                [
                    'docente_id' => $validatedData['user_id'],
                    'dictaminador_id' => $response->dictaminador_id,
                    'form_type' => 'form3_1',
                ],
                [
                    'docente_email' => $response->email,
                    'updated_at' => now(),
                ]
            );

            \Log::info('Datos insertados en dictaminador_docente');

            $this->checkAndTransfer('DictaminatorsResponseForm3_1');
            \Log::info('checkAndTransfer ejecutado');

            event(new EvaluationCompleted($validatedData['user_id']));
            \Log::info('Evento EvaluationCompleted disparado');

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

public function getFormData31(Request $request)
{
    try {
            \Log::info('DictaminatorForm3_1Controller::getFormData31 - Inicio', ['request' => $request->all()]);

            $query = DictaminatorsResponseForm3_1::query();

        if ($request->has('user_id')) {
            $query->where('user_id', $request->query('user_id'));
        } elseif ($request->has('email')) {
            $query->where('email', $request->query('email'));
        }

            $dictaminadorId = $request->query('dictaminador_id');

            // Intentar obtener primero el registro del dictaminador actual
            $data = $dictaminadorId ? (clone $query)->where('dictaminador_id', $dictaminadorId)->first() : null;

            // Si no se encuentra, buscar cualquier registro existente (de otro dictaminador)
            if (!$data) {
                $data = $query->first();
            }

        if (!$data) {
            \Log::info('DictaminatorForm3_1Controller::getFormData31 - Data not found');
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
                'form3_1' => [],
            ], 200);
        }

        \Log::info('DictaminatorForm3_1Controller::getFormData31 - Data found', ['id' => $data->id]);

        return response()->json([
            'success' => true,
            'data' => $data,
            'form3_1' => [$data]
        ]);
    } catch (\Exception $e) {
        \Log::error('DictaminatorForm3_1Controller::getFormData31 - Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}
    private function updateUserResponseComision($userId, $comisionValue)
    {
        // Buscar el registro de UsersResponseForm2 correspondiente y actualizar comision1
        \Log::info('Buscando registro en UsersResponseForm3_1 para user_id: ' . $userId);

                DB::transaction(function () use ($userId, $comisionValue) {
            // Actualiza/crea el registro específico del formulario de usuario
            $userResponse = UsersResponseForm3_1::updateOrCreate(
                ['user_id' => $userId],
                ['actv3Comision' => $comisionValue]
            );

            if ($userResponse) {
            $userResponse->actv3Comision = $comisionValue;
            $userResponse->save();
            \Log::info('Registro actualizado en UsersResponseForm3_1 para user_id: ' . $userId);

        } else {
            \Log::warning('No se encontró registro en UsersResponseForm3_1 para user_id: ' . $userId);
        }
        // Actualiza o inserta la fila consolidada (solo el campo de actv3Comision)
                    DB::table('consolidated_responses')->updateOrInsert(
                        ['user_id' => $userId],
                        [
                            'actv3Comision' => (float) $comisionValue,
                            'updated_at'  => now()
                        ]
                    );
                });

    }

    public function showForm31(Request $request)
    {
        // Definir el número de páginas (ajústalo según sea necesario)
        $currentPage = 3;  // La página actual para este formulario
        $totalPages = 2;   // Total de páginas en el formulario form3_1 (ajústalo según corresponda)
        
        // Pasar los valores de paginación a la vista
        return view('form3_1', compact('currentPage', 'totalPages'));
    }

    public function htmlToPdf(string $html): string
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $this->injectPageNumbers($dompdf);
        return $dompdf->output();
    }
    public function injectPageNumbers(Dompdf $dompdf): void
    {
        $canvas = $dompdf->getCanvas();
        // $pdf = $canvas->get_cpdf();
        $totalPages = 31;
        for ($pageNumber = 1; $pageNumber <= $totalPages; $pageNumber++) {
            $canvas->page_text(520, 820, "Page $pageNumber of $totalPages", null, 31, array(0, 0, 0));
        }
    }

    public function getTotalDocencia(Request $request)
    {
        $user_id = $request->input('user_id');
        $rows = DB::table('consolidated_responses')->where('user_id', $user_id)->get();

        if ($rows->isEmpty()) {
            return response()->json([
                'totalDocencia' => 0,
                'rowsCount' => 0,
                'breakdown' => []
            ]);
        }

                // Sumar campos asegurando cast a float
        $sum = function ($field) use ($rows) {
            return $rows->reduce(function ($carry, $row) use ($field) {
                return $carry + (float) ($row->{$field} ?? 0);
            }, 0.0);
        };


        $actv3Comision = $sum('actv3Comision');
        $com3_2 = $sum('comision3_2');
        $com3_3 = $sum('comision3_3');
        $com3_4 = $sum('comision3_4');
        $com3_5 = $sum('comision3_5');
        $com3_6 = $sum('comision3_6');
        $com3_7 = $sum('comision3_7');
        $com3_8 = $sum('comision3_8');
        $com3_8_1 = $sum('comision3_8_1');

        $sub1 = $actv3Comision + $com3_2 + $com3_3 + $com3_4 + $com3_5 + $com3_6 + $com3_7 + $com3_8 + $com3_8_1;

        $com3_9 = $sum('comision3_9');
        $com3_10 = $sum('comision3_10');
        $com3_11 = $sum('comision3_11');
        $sub2 = $com3_9 + $com3_10 + $com3_11;

        $com3_12 = $sum('comision3_12');
        $com3_13 = $sum('comision3_13');
        $com3_14 = $sum('comision3_14');
        $com3_15 = $sum('comision3_15');
        $com3_16 = $sum('comision3_16');
        $sub3 = $com3_12 + $com3_13 + $com3_14 + $com3_15 + $com3_16;

        $com3_17 = $sum('comision3_17');
        $com3_18 = $sum('comision3_18');
        $com3_19 = $sum('comision3_19');
        $sub4 = $com3_17 + $com3_18 + $com3_19;

        $rawTotal = $sub1 + $sub2 + $sub3 + $sub4;
        $total = min(round($rawTotal, 2), 700);


        return response()->json([
            'totalDocencia' => $total,
            'rawTotal' => round($rawTotal, 2),
            'rowsCount' => $rows->count(),
            'subtotals' => [
                'subtotal3_1To3_8_1' => round($sub1, 2),
                'subtotal3_9To3_11' => round($sub2, 2),
                'subtotal3_12To3_16' => round($sub3, 2),
                'subtotal3_17To3_19' => round($sub4, 2),
            ],
            'components' => [
                'actv3Comision' => round($actv3Comision, 2),
                'comision3_2' => round($com3_2, 2),
                'comision3_3' => round($com3_3, 2),
                'comision3_4' => round($com3_4, 2),
                'comision3_5' => round($com3_5, 2),
                'comision3_6' => round($com3_6, 2),
                'comision3_7' => round($com3_7, 2),
                'comision3_8' => round($com3_8, 2),
                'comision3_8_1' => round($com3_8_1, 2),
                'comision3_9' => round($com3_9, 2),
                'comision3_10' => round($com3_10, 2),
                'comision3_11' => round($com3_11, 2),
                'comision3_12' => round($com3_12, 2),
                'comision3_13' => round($com3_13, 2),
                'comision3_14' => round($com3_14, 2),
                'comision3_15' => round($com3_15, 2),
                'comision3_16' => round($com3_16, 2),
                'comision3_17' => round($com3_17, 2),
                'comision3_18' => round($com3_18, 2),
                'comision3_19' => round($com3_19, 2),
            ],
            'rows' => $rows, // para inspección en el cliente
        ]);
    }

    public function getTotalDocenciaEvaluar(Request $request)
{
    $user_id = $request->input('user_id');
    $total = 0;

    $total += (UsersResponseForm3_1::where('user_id', $user_id)->value('score3_1') ?? 0);
    $total += (UsersResponseForm3_2::where('user_id', $user_id)->value('score3_2') ?? 0);
    $total += (UsersResponseForm3_3::where('user_id', $user_id)->value('score3_3') ?? 0);
    $total += (UsersResponseForm3_4::where('user_id', $user_id)->value('score3_4') ?? 0);
    $total += (UsersResponseForm3_5::where('user_id', $user_id)->value('score3_5') ?? 0);
    $total += (UsersResponseForm3_6::where('user_id', $user_id)->value('score3_6') ?? 0);
    $total += (UsersResponseForm3_7::where('user_id', $user_id)->value('score3_7') ?? 0);
    $total += (UsersResponseForm3_8::where('user_id', $user_id)->value('score3_8') ?? 0);
    $total += (UsersResponseForm3_8_1::where('user_id', $user_id)->value('score3_8_1') ?? 0);
    $total += (UsersResponseForm3_9::where('user_id', $user_id)->value('score3_9') ?? 0);
    $total += (UsersResponseForm3_10::where('user_id', $user_id)->value('score3_10') ?? 0);
    $total += (UsersResponseForm3_11::where('user_id', $user_id)->value('score3_11') ?? 0);
    $total += (UsersResponseForm3_12::where('user_id', $user_id)->value('score3_12') ?? 0);
    $total += (UsersResponseForm3_13::where('user_id', $user_id)->value('score3_13') ?? 0);
    $total += (UsersResponseForm3_14::where('user_id', $user_id)->value('score3_14') ?? 0);
    $total += (UsersResponseForm3_15::where('user_id', $user_id)->value('score3_15') ?? 0);
    $total += (UsersResponseForm3_16::where('user_id', $user_id)->value('score3_16') ?? 0);
    $total += (UsersResponseForm3_17::where('user_id', $user_id)->value('score3_17') ?? 0);
    $total += (UsersResponseForm3_18::where('user_id', $user_id)->value('score3_18') ?? 0);
    $total += (UsersResponseForm3_19::where('user_id', $user_id)->value('score3_19') ?? 0);
  
    $total = min($total, 700);

    return response()->json(['totalDocencia' => $total]);
}

    public function showForm31NoSearch(Request $request, $teacherEmail = null)
    {
        // Si se proporciona un email de docente en la URL, no necesitamos mostrar el buscador.
        // El script de autocompletado cargará los datos automáticamente.
        $showSearchComponent = is_null($teacherEmail);

        $hasData = false;
        $docencia = 0;
        $score3_1 = 0;

        if ($teacherEmail) {
            $user = \App\Models\User::where('email', $teacherEmail)->first();
            if ($user) {
                $hasData = DictaminatorsResponseForm3_1::where('email', $teacherEmail)
                    ->exists();

                // Reutilizar la lógica para obtener los puntajes
                $responseJsonController = new ResponseJson();
                $scores = $responseJsonController->buildDocenciaScores($user->id);
                $docencia = $scores['docencia'] ?? 0;
                $score3_1 = $scores['score3_1'] ?? 0;
            }
        }
        return view('form3_1', [
            'teacherEmailFromUrl' => $teacherEmail,
            'showSearch' => $showSearchComponent,
            'hasData' => $hasData,
            'docencia' => $docencia,
            'score3_1' => $score3_1,
        ]);
    }

    public function updateform31(Request $request)
{
    // Validar los datos de entrada
    $validatedData = $request->validate(self::getValidationRules());

    try {
        // Buscar el registro existente por user_id y dictaminador_id
        $response = DictaminatorsResponseForm3_1::updateOrCreate(
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
        \Log::error('Error al actualizar el formulario 3.1: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Ocurrió un error en el servidor al actualizar.'
        ], 500);
    }
}
}
