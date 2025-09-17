<?php
/**
Nombre del programador: José Antonio Martínez del Toro
objetivo: Controlador para la gestión de usuarios
Fecha de creación: 2024-06-03
 */
namespace App\Http\Controllers;

use App\Models\User; // Import the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Import Hash facade

class UserController extends Controller
{
    public function index()
    {
        $datos = User::all(); // Using Eloquent instead of direct SQL queries
        return view("users")->with("datos", $datos);
    }

    public function add(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'user_type' => $request->user_type,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            return back()->with("correcto", "¡Usuario registrado correctamente!");
        } else {
            return back()->with("incorrecto", "Error al registrar un usuario, por favor verifique la información.");
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->id,
            'password' => 'sometimes|string|min:8',
        ]);

        $user = User::find($request->id);
        if ($user) {
            $user->email = $request->email;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            return back()->with("correcto", "¡Datos de usuario editados correctamente!");
        } else {
            return back()->with("incorrecto", "Error al editar un usuario, por favor verifique la información.");
        }
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return back()->with("correcto", "¡Usuario eliminado correctamente!");
        } else {
            return back()->with("incorrecto", "Error al eliminar un usuario, por favor verifique la información.");
        }
    }
}
