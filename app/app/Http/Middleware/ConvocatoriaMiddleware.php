<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ConvocatoriaMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $convocatoria = \App\Models\UsersResponseForm1::where('user_id', $userId)->first();
            view()->share('convocatoria', $convocatoria);
            
        }

        return $next($request);
    }
}
