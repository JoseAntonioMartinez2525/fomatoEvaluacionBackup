<?php
/**
Nombre del programador: José Antonio Martínez del Toro
objetivo: Controlador para la gestión de usuarios
Fecha de creación: 2024-06-03
 */
namespace App\Http\Controllers;

use App\Models\User; // Import the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Hash; // Import Hash facade
use Dompdf\Dompdf;
use ZipArchive;
use App\Jobs\GenerateReportsJob;
use App\Models\GeneratedReport;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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
            'user_type' => 'docente',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            Auth::login($user);
            return redirect()->route('welcome');
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

    public function import() 
    {
        Excel::import(new UsersImport, 'users.xlsx');
        
        return redirect('/')->with('success', 'All good!');
    }

    public function export() 
    {
        // Verificar si el usuario tiene permiso para exportar (está en la lista de permitidos)
        if (!in_array(Auth::user()->email, SessionsController::$allowedEmails)) {
            return redirect()->back()->with('incorrecto', 'Acceso denegado: No tiene permisos para generar este reporte.');
        }

        // 1. Crear un registro en la base de datos para rastrear este trabajo.
        $reportRecord = GeneratedReport::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
            'file_name' => 'Reportes PEDPD - ' . now()->format('Y-m-d H-i-s') . '.zip',
        ]);

        // 2. Despachar el trabajo a la cola.
        GenerateReportsJob::dispatch(Auth::user(), $reportRecord);

        // 3. Redirigir al usuario de vuelta al dashboard (secretaria) con un mensaje.
        return redirect()->route('secretaria')
            ->with('correcto', 'La generación de reportes ha comenzado en segundo plano. Verifique la sección de "Reportes Generados" en unos momentos.');
    }

}
