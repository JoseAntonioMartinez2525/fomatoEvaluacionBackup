<?php

namespace App\Http\Controllers;


use App\Events\EvaluationCompleted;
use App\Models\UsersResponseForm3_2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResponseForm3_2Controller extends Controller
{
    public function store32(Request $request)
    {
        // Validate request data
       try { $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'email' => 'required|exists:users,email',
            'score3_2' => 'required|numeric',
            //'comision3_2' => 'required|numeric',
            'r1' => 'required|numeric',
            'r2' => 'required|numeric',
            'r3' => 'required|numeric',
            'cant1' => 'required|numeric',
            'cant2' => 'required|numeric',
            'cant3' => 'required|numeric',
            'obs3_2_1' => 'nullable|string',
            'obs3_2_2' => 'nullable|string',
            'obs3_2_3' => 'nullable|string',
            'user_type' => 'required|in:user,docente,dictaminator',

        ]);

            $validatedData['form_type'] = 'form3_2';
        // Assign default value if not provided
        $validatedData['obs3_2_1'] = $validatedData['obs3_2_1'] ?? 'sin comentarios';
        $validatedData['obs3_2_2'] = $validatedData['obs3_2_2'] ?? 'sin comentarios';
        $validatedData['obs3_2_3'] = $validatedData['obs3_2_3'] ?? 'sin comentarios';

            $docenteData = DB::table('dictaminators_response_form3_2')
                ->where('user_id', $validatedData['user_id'])
                ->select('comision3_2')
                ->first();
                
            // Pasar el valor a $validatedData para asegurar que esté disponible en la vista
            $validatedData['comision3_2'] = $docenteData->comision3_2 ?? null;
            // Create a new record using Eloquent ORM
            UsersResponseForm3_2::create($validatedData);

            // Disparar evento después de la creación del registro
            event(new EvaluationCompleted($validatedData['user_id']));

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

    public function getData32(Request $request)
    {


        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'user_type' => 'required|in:user,docente,dictaminator', // Nuevo campo para distinguir entre usuarios
        ]);

        // Obtener datos de la tabla correspondiente según el tipo de usuario
        if ($validatedData['user_type'] == 'docente') {
            $data = UsersResponseForm3_2::where('user_id', $request->query('user_id'))->first();
        } elseif ($validatedData['user_type'] == 'dictaminator') {
            //$data = DictaminatorsResponseForm3_3::where('user_id', $request->query('user_id'))->first();
        }
        return response()->json($data);
    }

}