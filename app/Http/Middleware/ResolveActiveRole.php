<?php

namespace App\Http\Middleware;

use App\Models\EvaluationDate;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ResolveActiveRole
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        $now = now();

        $isDictaminador = $user->is_dictaminador;
        $isDocente = array_key_exists(
            strtolower($user->email),
            config('docentes', [])
        );

        $dictaminadorPeriod = EvaluationDate::where(
            'type',
            'dictaminadores_capturando_datos'
        )->latest('id')->first();

        $inDictaminadorPeriod =
            $dictaminadorPeriod &&
            $now->between(
                $dictaminadorPeriod->start_date,
                Carbon::parse($dictaminadorPeriod->end_date)->endOfDay()
            );

        if ($isDictaminador && $inDictaminadorPeriod) {
            session(['active_role' => 'dictaminador']);
        } else {
            session(['active_role' => 'docente']);
        }

        return $next($request);
    }
}

