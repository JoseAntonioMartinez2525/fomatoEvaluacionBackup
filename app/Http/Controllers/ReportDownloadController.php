<?php

namespace App\Http\Controllers;

use App\Models\GeneratedReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportDownloadController extends Controller
{
    public function index()
    {
        // Muestra los reportes generados por el usuario actual, del más reciente al más antiguo.
        $reports = GeneratedReport::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('reports.index', compact('reports'));
    }

    public function download(GeneratedReport $report)
    {
        // Asegurarse de que el usuario solo pueda descargar sus propios reportes.
        if ($report->user_id !== Auth::id()) {
            abort(403, 'Acceso no autorizado.');
        }

        // Verificar que el reporte esté completo y el archivo exista.
        if ($report->status !== 'completed' || !file_exists($report->file_path)) {
            return redirect()->route('reports.index')->with('incorrecto', 'El reporte no está listo o no se encontró el archivo.');
        }

        return response()->download($report->file_path, $report->file_name);
    }
}

