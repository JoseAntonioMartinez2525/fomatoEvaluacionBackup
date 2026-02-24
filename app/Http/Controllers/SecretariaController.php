<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\GeneratedReport; // Importar el modelo
use Illuminate\Support\Facades\Auth; // Importar Auth

class SecretariaController extends Controller
{
    public function showSecretaria()
    {
        $users = User::where('user_type', 'dictaminador')->get();
        // Obtener los últimos 5 reportes generados por el usuario actual para mostrarlos en el dashboard
        $reports = GeneratedReport::where('user_id', Auth::id())->latest()->take(5)->get();
        
        return view('secretaria', compact('users', 'reports'));
    }
}
