<?php

namespace App\Http\Controllers;

use App\Models\UserResume;
use App\Models\EvaluatorSignature;
use App\Models\Users;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function showProfile(Request $request)
    {
        $userId = $request->input('user_id');
        $email = $request->input('email');
        $userType = $request->query('user_type');

        // Validar los datos de entrada
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'email' => 'required|exists:users,email',
            'user_type' => 'required|exists:users,user_type',
        ]);

        // Obtener el usuario
        $user = Users::where('email', $email)
            ->where('user_type', $userType)
            ->first();

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado.'], 404);
        }

        // Obtener datos del formulario 4 (UserResume)
        $userResume = UserResume::where('user_id', $userId)
            ->where('email', $email)
            ->first();

        // Obtener datos del formulario 5 (EvaluatorSignature)
        $evaluatorSignature = EvaluatorSignature::where('user_id', $userId)
            ->where('email', $email)
            ->first();

        if (!$userResume || !$evaluatorSignature) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }

        // Pasar los datos a la vista
        return view('perfil', [
            'user' => $user,
            'userResume' => $userResume,
            'evaluatorSignature' => $evaluatorSignature,
        ]);
    }
    public function showAllUsers()
    {
        // Obtener todos los registros de UserResume y EvaluatorSignature
        $userResumes = UserResume::all();
        $evaluatorSignatures = EvaluatorSignature::all();

        // Pasar los datos a la vista
        return view('general', [
            'userResumes' => $userResumes,
            'evaluatorSignatures' => $evaluatorSignatures,
        ]);
    }
}
