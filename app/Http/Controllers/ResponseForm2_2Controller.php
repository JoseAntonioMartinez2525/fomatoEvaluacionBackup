<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\UsersResponseForm2_2;
use App\Models\DictaminatorsResponseForm2_2;
use Illuminate\Support\Facades\DB;

class ResponseForm2_2Controller extends Controller
{
    public function store3(Request $request)
    {
        try {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'email' => 'required|exists:users,email',
            'hours' => 'required|numeric',
            'horasPosgrado' => 'required|numeric',
            'horasSemestre' => 'required|numeric',
            'dse' => 'required|numeric',
            'dse2' => 'required|numeric',
            'obs2' => 'nullable|string',
            'obs2_2' => 'nullable|string',
            'user_type' => 'required|in:user,docente,dictaminator',
        ]);

            $validatedData['form_type'] = 'form2_2';
        // Assign a default value if hours is not provided
        if (!isset($validatedData['hours'])) {
            $validatedData['hours'] = 0;
        }
        $validatedData['obs2'] = $validatedData['obs2'] ?? 'sin comentarios';
        $validatedData['obs2_2'] = $validatedData['obs2_2'] ?? 'sin comentarios';

            $validatedData['horasSemestre'] = (float) $validatedData['horasSemestre'] ?? 0.0;
            $validatedData['horasPosgrado'] = (float) $validatedData['horasPosgrado'] ?? 0.0;
            
            // Consulta de datos con unión
            $docenteData = DB::table('dictaminators_response_form2_2')
                ->where('user_id', $validatedData['user_id'])
                ->select('actv2Comision')
                ->first();
            // Pasar el valor a $validatedData para asegurar que esté disponible en la vista
            $validatedData['actv2Comision'] = $docenteData->actv2Comision ?? null;
        // Guardar en la tabla correspondiente según el tipo de usuario

        UsersResponseForm2_2::create($validatedData);

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

    public function getData22(Request $request)
    {

        $data = UsersResponseForm2_2::where('user_id', $request->query('user_id'))->first();
        return response()->json($data);
    }
}

