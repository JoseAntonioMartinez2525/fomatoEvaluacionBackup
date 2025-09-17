<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use Illuminate\Http\Request;
use App\Models\UsersResponseForm2;
use App\Models\DictaminatorsResponseForm2;
use Illuminate\Support\Facades\DB;

class ResponseForm2Controller extends Controller
{
    public function store2(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'email' => 'required|exists:users,email',
                'horasActv2' => 'required|numeric',
                'puntajeEvaluar' => 'required|numeric', // Allow nullable
                'obs1' => 'nullable|string',
                'user_type' => 'required|in:user,docente,dictaminator',
            ]);

            $validatedData['form_type'] = 'form2';
            // Assign a default value if puntajeEvaluar is not provided
            if (!isset($validatedData['puntajeEvaluar'])) {
                $validatedData['puntajeEvaluar'] = 0;
            }
            if (!isset($validatedData['obs1'])) {
                $validatedData['obs1'] = "sin comentarios";
            }

            $horasActv2 = $validatedData['horasActv2'] ?? 0.0;

            // Busca la comision1 en dictaminators_response_form2
            $docenteData = DB::table('dictaminators_response_form2')
                ->where('user_id', $validatedData['user_id'])
                ->select('comision1')
                ->first();

            $validatedData['comision1'] = $docenteData->comision1 ?? null;

            UsersResponseForm2::create($validatedData);


            // Disparar evento después de la creación del registro
            event(new EvaluationCompleted($validatedData['user_id']));

            $consolidatedData = DB::table('consolidated_responses')
                ->where('user_id', $validatedData['user_id'])
                ->first();

            if ($consolidatedData) {
                // Actualiza el registro del docente con la comision1
                UsersResponseForm2::where('user_id', $validatedData['user_id'])
                    ->update(['comision1' => $consolidatedData->comision1]);
            }

   

            return response()->json([
                'success' => true,
                'message' => 'Form submitted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }



    public function getData2(Request $request)
    {
        $data = UsersResponseForm2::where('email', $request->query('email'))->first();


        return response()->json($data);
    }

    
    }

