<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\DictaminatorsResponseForm3_1;
use App\Models\UsersResponseForm3_1;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;


class DictaminatorForm3_1Controller extends TransferController
{
    public function storeform31(Request $request)
    {
        
        
        try {
            \Log::info('Inicio de storeform31');

            $validatedData = $request->validate([
                'dictaminador_id' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'elaboracion' => 'required|numeric',
                'elaboracionSubTotal1' => 'required|numeric', // Allow nullable
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
                'obs3_1_1' => 'nullable|string',
                'obs3_1_2' => 'nullable|string',
                'obs3_1_3' => 'nullable|string',
                'obs3_1_4' => 'nullable|string',
                'obs3_1_5' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
            ]);

            \Log::info('Datos validados:', $validatedData);

            $validatedData['form_type'] = 'form3_1';
            
            if (!isset($validatedData['score3_1'])) {
                $validatedData['score3_1'] = 0;
            }
            $validatedData['obs3_1_1'] = $validatedData['obs3_1_1'] ?? 'sin comentarios';
            $validatedData['obs3_1_2'] = $validatedData['obs3_1_2'] ?? 'sin comentarios';
            $validatedData['obs3_1_3'] = $validatedData['obs3_1_3'] ?? 'sin comentarios';
            $validatedData['obs3_1_4'] = $validatedData['obs3_1_4'] ?? 'sin comentarios';
            $validatedData['obs3_1_5'] = $validatedData['obs3_1_5'] ?? 'sin comentarios';

            
            $response = DictaminatorsResponseForm3_1::create($validatedData);
            \Log::info('Datos guardados en DictaminatorsResponseForm3_1:', $response->toArray());

            $this->updateUserResponseComision($validatedData['user_id'], $validatedData['actv3Comision']);
            \Log::info('updateUserResponseComision ejecutado');

            DB::table('dictaminador_docente')->insert([
                'user_id' => $validatedData['user_id'], // Asegúrate de que este ID exista
                'dictaminador_id' => $response->dictaminador_id,
                'form_type' => 'form3_1', // O el tipo de formulario correspondiente
                'docente_email' => $response->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            \Log::info('Datos insertados en dictaminador_docente');

            $this->checkAndTransfer('DictaminatorsResponseForm3_1');
            \Log::info('checkAndTransfer ejecutado');

            event(new EvaluationCompleted($validatedData['user_id']));
            \Log::info('Evento EvaluationCompleted disparado');

            return response()->json([
                'success' => true,
                'message' => 'Data successfully saved',
                'data' => $validatedData,
            ], 200);
        } catch (ValidationException $e) {
            \Log::error('ValidationException:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            \Log::error('QueryException:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            \Log::error('Exception:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getFormData31(Request $request)
    {
        try {
            $data = DictaminatorsResponseForm3_1::where('user_id', $request->query('user_id'))->first();
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

    private function updateUserResponseComision($userId, $comisionValue)
    {
        // Buscar el registro de UsersResponseForm2 correspondiente y actualizar comision1
        \Log::info('Buscando registro en UsersResponseForm3_1 para user_id: ' . $userId);

        $userResponse = UsersResponseForm3_1::where('user_id', $userId)->first();

        if ($userResponse) {
            $userResponse->actv3Comision = $comisionValue;
            $userResponse->save();
            \Log::info('Registro actualizado en UsersResponseForm3_1 para user_id: ' . $userId);

        } else {
            \Log::warning('No se encontró registro en UsersResponseForm3_1 para user_id: ' . $userId);
        }
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
        $pdf = $canvas->get_cpdf();
        $totalPages = 31;
        for ($pageNumber = 1; $pageNumber <= $totalPages; $pageNumber++) {
            $canvas->page_text(520, 820, "Page $pageNumber of $totalPages", null, 31, array(0, 0, 0));
        }
    }

    public function getTotalDocencia(Request $request)
    {
            $user_id = $request->query('user_id');
        $consolidatedResponses = DB::table('consolidated_responses')->where('user_id', $user_id)->get();

        $subtotal3_1To3_8_1 = $consolidatedResponses->reduce(function ($carry, $response) {
            return $carry
                + ($response->actv3Comision ?? 0)
                + ($response->comision3_2 ?? 0)
                + ($response->comision3_3 ?? 0)
                + ($response->comision3_4 ?? 0)
                + ($response->comision3_5 ?? 0)
                + ($response->comision3_6 ?? 0)
                + ($response->comision3_7 ?? 0)
                + ($response->comision3_8 ?? 0)
                + ($response->comision3_8_1 ?? 0);
        }, 0);

        $subtotal3_9To3_11 = $consolidatedResponses->reduce(function ($carry, $response) {
            return $carry
                + ($response->comision3_9 ?? 0)
                + ($response->comision3_10 ?? 0)
                + ($response->comision3_11 ?? 0);
        }, 0);

        $subtotal3_12To3_16 = $consolidatedResponses->reduce(function ($carry, $response) {
            return $carry
                + ($response->comision3_12 ?? 0)
                + ($response->comision3_13 ?? 0)
                + ($response->comision3_14 ?? 0)
                + ($response->comision3_15 ?? 0)
                + ($response->comision3_16 ?? 0);
        }, 0);

        $subtotal3_17To3_19 = $consolidatedResponses->reduce(function ($carry, $response) {
            return $carry
                + ($response->comision3_17 ?? 0)
                + ($response->comision3_18 ?? 0)
                + ($response->comision3_19 ?? 0);
        }, 0);

        $total = min(
            $subtotal3_1To3_8_1 + $subtotal3_9To3_11 + $subtotal3_12To3_16 + $subtotal3_17To3_19,
            700
        );

            return response()->json(['totalDocencia' => 60]);

    }
}


