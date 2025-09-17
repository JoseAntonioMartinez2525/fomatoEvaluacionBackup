<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function toggleDarkMode(Request $request)
    {
        // Guardamos la preferencia en la sesión
        session(['dark_mode' => $request->dark_mode]);

        // Retornamos una respuesta en formato JSON
        return response()->json(['success' => true]);
    }
}
