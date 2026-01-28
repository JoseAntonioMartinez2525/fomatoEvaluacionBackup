<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EvaluationDate; // Asegúrate de importar el modelo
use Carbon\Carbon;

class CheckEvaluationPeriod
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Aplicar la lógica solo para los docentes
        if ($user && $user->user_type === 'docente') {
            $evaluationDates = EvaluationDate::where('type', 'docentes_llenado')->latest('id')->first();
            $now = Carbon::now();

            if ($evaluationDates) {
                $startDate = Carbon::parse($evaluationDates->start_date);
                $endDate = Carbon::parse($evaluationDates->end_date)->endOfDay(); // Incluir todo el día final

                // Si la fecha actual NO está dentro del rango, bloquear el acceso.
                if (!$now->between($startDate, $endDate)) {
                    // Puedes redirigir o abortar la petición.
                    // Abortar con una página de error es más claro.
                    return response()->view('errors.period-closed', [], 403);
                }
            } else {
                // Si no hay fechas configuradas, por defecto se bloquea el acceso.
                return response()->view('errors.period-closed', ['message' => 'El período de evaluación aún no ha sido configurado.'], 403);
            }
        }

        // Si no es docente o está dentro del período, permite continuar.
        return $next($request);
    }
}
