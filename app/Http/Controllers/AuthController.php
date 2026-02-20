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
            'registerName' => 'required|string|max:255', // Nombre del usuario
            'registerEmail' => 'required|string|email|max:255', // Correo electrónico (ya no es único)
            'registerPassword' => 'required|string|min:8|confirmed', // Contraseña (mínimo 8 caracteres)
        ],[
            'registerName.required' => 'El nombre es obligatorio.',
            'registerEmail.required' => 'El correo electrónico es obligatorio.',
            'registerEmail.email' => 'El correo electrónico debe ser una dirección válida.',
            'registerPassword.required' => 'La contraseña es obligatoria.',
            'registerPassword.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'registerPassword.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        // 🔹 USO DEL NORMALIZADOR (única fuente de verdad)
        $email = strtolower($request->registerEmail);
        $name = $request->registerName;
        $password = Hash::make($request->registerPassword);

        // Determinar si el usuario es dictaminador o docente según la configuración
        $dictaminadorConfig = DictaminadoresConfig::byEmail($email);
        $isDictaminador = (bool) $dictaminadorConfig;
        $isDocenteConfig = array_key_exists($email, config('docentes', []));
        $userType = ($isDictaminador && !$isDocenteConfig) ? 'dictaminador' : 'docente';
        $departamento = $dictaminadorConfig['departamento'] ?? null;

        // Buscar si el usuario ya existe
        $user = User::where('email', $email)->first();

        if ($user) {
            // Si el usuario existe, actualizar solo su nombre y contraseña.
            // Preservamos departamento y roles que vienen de la sincronización.
            $user->update([
                'name' => $name,
                'password' => $password,
            ]);
        } else {
            // Si el usuario no existe, crearlo
            $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'user_type'       => $userType,
            'is_dictaminador' => $isDictaminador,
            'departamento'    => $departamento,
            ]);
        }

        return redirect()->route('login');
    }
}
