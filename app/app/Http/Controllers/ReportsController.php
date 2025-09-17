<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserResume;
use App\Models\UsersResponseForm1;
use App\Models\EvaluatorSignature;

class ReportsController extends Controller
{
    public function index()
    {
        // Correos no permitidos
        $notAllowedEmails = [
            'joma_18@alu.uabcs.mx',
            'oa.campillo@uabcs.mx',
            'rluna@uabcs.mx',
            'v.andrade@uabcs.mx'
        ];

        // Obtener todos los usuarios excluyendo los correos no permitidos
        $users = User::whereNotIn('email', $notAllowedEmails)->get();
        return view('general', compact('users'));
    }

    public function showProfile(Request $request)
    { {
            $email = $request->input('email');

            // Obtener datos del perfil del usuario
            $user = User::where('email', $email)->first();
            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            $resume = UserResume::where('user_id', $user->id)->first();
            $responseForm1 = UsersResponseForm1::where('user_id', $user->id)->first();
            $signature = EvaluatorSignature::where('user_id', $user->id)->where('email', $email)->first();

            return response()->json([
                'resume' => $resume,
                'responseForm1' => $responseForm1,
                'signature' => $signature,
                'user' => $user
            ]);
        }
    }
}

