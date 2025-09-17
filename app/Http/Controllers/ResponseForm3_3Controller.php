<?php

namespace App\Http\Controllers;

use App\Events\EvaluationCompleted;
use App\Models\UsersResponseForm3_3;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResponseForm3_3Controller extends Controller
{
    public function store33(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'email' => 'required|exists:users,email',
            'score3_3' => 'required|numeric',
            'rc1' => 'required|numeric',
            'rc2' => 'required|numeric',
            'rc3' => 'required|numeric',
            'rc4' => 'required|numeric',
            'stotal1' => 'required|numeric',
            'stotal2' => 'required|numeric',
            'stotal3' => 'required|numeric',
            'stotal4' => 'required|numeric',
            'obs3_3_1' => 'nullable|string',
            'obs3_3_2' => 'nullable|string',
            'obs3_3_3' => 'nullable|string',
            'obs3_3_4' => 'nullable|string',
            'user_type' => 'required|in:user,docente,dictaminator',

        ]);

        $validatedData['form_type'] = 'form3_3';
        // Assign default value if not provided
        $validatedData['obs3_3_1'] = $validatedData['obs3_3_1'] ?? 'sin comentarios';
        $validatedData['obs3_3_2'] = $validatedData['obs3_3_2'] ?? 'sin comentarios';
        $validatedData['obs3_3_3'] = $validatedData['obs3_3_3'] ?? 'sin comentarios';
        $validatedData['obs3_3_4'] = $validatedData['obs3_3_4'] ?? 'sin comentarios';

        try {

            $docenteData = DB::table('dictaminators_response_form3_3')
                ->where('user_id', $validatedData['user_id'])
                ->select('comision3_3')
                ->first();


            // Pasar el valor a $validatedData para asegurar que esté disponible en la vista
                $validatedData['comision3_3'] = $docenteData->comision3_3 ?? null;
            UsersResponseForm3_3::create($validatedData);

            // Disparar evento después de la creación del registro
            event(new EvaluationCompleted($validatedData['user_id']));

            return response()->json([
                'success' => true,
                'message' => 'Form submitted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting form: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getData33(Request $request)
    {

        $data = UsersResponseForm3_3::where('user_id', $request->query('user_id'))->first();
        return response()->json($data);
    }
}