<?php
/*
Nombre del programador: José Antonio Martínez del Toro
Objetivo: Implementación backend del registro de usuarios
Fecha de creación: 2024-06-03
*/
namespace App\Http\Controllers;

use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 

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
            'registerUsertype' => 'required|in:dictaminador,docente',
            'registerEmail' => 'required|string|email|max:255|unique:users,email',
            'registerPassword' => 'required|string|min:6|confirmed',
        ],[
            'registerName.required' => 'El nombre es obligatorio.',
            'registerUsertype.required' => 'El tipo de usuario es obligatorio.',
            'registerEmail.required' => 'El correo electrónico es obligatorio.',
            'registerPassword.required' => 'La contraseña es obligatoria.',
            'registerPassword.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'registerPassword.confirmed' => 'Las contraseñas no coinciden.',
    ]);


        // Create the new user
        $user = new User();
        $user->name = $request->registerName;
        $user->user_type = $request->registerUsertype;
        $user->email = $request->registerEmail;
        $user->password = Hash::make($request->registerPassword);
        // Use Hash to encrypt the password
        $user->save();


        // Redirect or login the user
        return redirect()->route('login')->with('success', 'Registro exitoso. Por favor inicia sesión.');
    }
}
