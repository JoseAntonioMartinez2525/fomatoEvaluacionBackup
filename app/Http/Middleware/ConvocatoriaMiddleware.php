<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UsersResponseForm1;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ConvocatoriaMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = null;
        if (Auth::check()) {
            $user = Auth::user();
        } elseif ($request->has('email')) {
            $user = User::where('email', $request->email)->first();
        }

        $convocatoria = null;
        $periodo = null;

        if ($user) {
            $form1 = UsersResponseForm1::where('user_id', $user->id)->first();
            $convocatoria = $form1?->convocatoria;
            $periodo = $form1?->periodo;
        }

        // Si no hay periodo específico (ej. secretaria o dictaminador sin form1), usar el global calculado
        if (!$periodo) {
            $periodo = UsersResponseForm1::calculateCurrentPeriod() ?? 'Periodo no definido';
        }

        // Compartir globalmente con todas las vistas
        View::share('convocatoria', $convocatoria);
        View::share('periodo', $periodo);

        return $next($request);
    }
}
