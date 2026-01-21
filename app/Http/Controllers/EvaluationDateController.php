<?php

namespace App\Http\Controllers;

use App\Models\EvaluationDate;
use Illuminate\Http\Request;

class EvaluationDateController extends Controller
{
    public function getFechas()
    {
        // Obtener la última fecha registrada para cada tipo (ordenado por ID descendente)
        $docentesLlenado = EvaluationDate::where('type', 'docentes_llenado')->latest('id')->first();
        $docentesEvaluacion = EvaluationDate::where('type', 'docentes_evaluacion')->latest('id')->first();
        $evaluadoresCaptura = EvaluationDate::where('type', 'evaluadores_captura')->latest('id')->first();

        return response()->json([
            'docentes_llenado' => $docentesLlenado,
            'dictaminadores_capturando_datos' => $docentesEvaluacion, // Mapeo para el frontend
            'files_capture_dates' => $evaluadoresCaptura, // Mapeo para el frontend
        ]);
    }

    public function storeDocentesLlenado(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // CORRECCIÓN: Usar create() para generar un nuevo registro y mantener historial
        EvaluationDate::create([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => 'docentes_llenado',
        ]);

        return response()->json(['success' => true]);
    }

    public function storeDocentesEvaluacion(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        EvaluationDate::updateOrCreate(
            ['type' => 'docentes_evaluacion'],
            ['start_date' => $request->start_date, 'end_date' => $request->end_date]
        );

        return response()->json(['success' => true]);
    }

    public function storeEvaluadoresCaptura(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        EvaluationDate::updateOrCreate(
            ['type' => 'evaluadores_captura'],
            ['start_date' => $request->start_date, 'end_date' => $request->end_date]
        );

        return response()->json(['success' => true]);
    }
}