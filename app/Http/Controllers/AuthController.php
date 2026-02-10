<?php
/*
Nombre del programador: José Antonio Martínez del Toro
Objetivo: Implementación backend del registro de usuarios
Fecha de creación: 2024-06-03
*/
namespace App\Http\Controllers;

use App\Support\DictaminadoresConfig;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        // Validate and register the user
        //dd($request->all());
        $request->validate([
            'registerName' => 'required|string|max:255',
            //'registerUsertype' => 'required|in:dictaminador,docente',
            'registerEmail' => 'required|string|email|max:255|unique:users,email',
            'registerPassword' => 'required|string|min:6|confirmed',
        ],[
            'registerName.required' => 'El nombre es obligatorio.',
            //'registerUsertype.required' => 'El tipo de usuario es obligatorio.',
            'registerEmail.required' => 'El correo electrónico es obligatorio.',
            'registerPassword.required' => 'La contraseña es obligatoria.',
            'registerPassword.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'registerPassword.confirmed' => 'Las contraseñas no coinciden.',
    ]);

            // 🔹 USO DEL NORMALIZADOR (única fuente de verdad)
            $email = strtolower($request->registerEmail);
            $dictaminador = DictaminadoresConfig::byEmail($email);

            $isDictaminador = (bool) $dictaminador;
            
            // Verificar si está en la configuración de docentes
            $isDocenteConfig = array_key_exists($email, config('docentes', []));

            // Determinar tipo de usuario: si es dictaminador puro (y no está en docentes), asignar 'dictaminador', sino 'docente'
            $userType = ($isDictaminador && !$isDocenteConfig) ? 'dictaminador' : 'docente';

            $user = User::create([
            'name'            => $request->registerName,
            'email'           => $email,
            'password'        => Hash::make($request->registerPassword),
            'user_type'       => $userType,
            'is_dictaminador' => $isDictaminador,
            'departamento'    => $dictaminador['departamento'] ?? null,
        ]);

        Auth::login($user);

        return redirect()->route('welcome');
    }
}