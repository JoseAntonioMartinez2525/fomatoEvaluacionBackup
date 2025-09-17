<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DarkModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el modo oscuro está activado en la sesión o en una cookie
        if (session('dark_mode', false)) {
            // Añadir una clase para aplicar el modo oscuro
            view()->share('darkMode', true);
        } else {
            view()->share('darkMode', false);
        }
        return $next($request);
    }
}
