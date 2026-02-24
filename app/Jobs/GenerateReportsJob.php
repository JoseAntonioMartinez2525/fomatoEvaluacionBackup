<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\GeneratedReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Dompdf\Dompdf;
use ZipArchive;
use Throwable;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class GenerateReportsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $requestingUser;
    protected GeneratedReport $reportRecord;

    /**
     * Create a new job instance.
     */
    public function __construct(User $requestingUser, GeneratedReport $reportRecord)
    {
        $this->requestingUser = $requestingUser;
        $this->reportRecord = $reportRecord;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->reportRecord->update(['status' => 'processing']);
        $tempPath = '';

        try {
            // 1. Configuración inicial y directorios
            $timestamp = time();
            $tempDirName = 'temp_export_' . $timestamp;
            $tempPath = storage_path('app/' . $tempDirName);
            File::ensureDirectoryExists($tempPath, 0755, true);

            $reportsDir = storage_path('app/public/generated_reports');
            File::ensureDirectoryExists($reportsDir, 0755, true);

            // 2. Obtener usuarios y datos globales
            \Log::info('GenerateReportsJob: Buscando usuarios para exportar.');
            
            // Obtener IDs de usuarios que ya han guardado datos generales (Formulario 1)
            // Esto asegura incluir a usuarios con doble rol que actualmente sean 'dictaminador'
            $activeDocenteIds = \App\Models\UsersResponseForm1::pluck('user_id')->toArray();

            $users = User::where(function ($query) use ($activeDocenteIds) {
                $query->where('user_type', 'docente')
                      ->orWhereIn('id', $activeDocenteIds);
            })->get();

            \Log::info('GenerateReportsJob: Se encontraron ' . $users->count() . ' usuarios.');

            // Si no se encuentran usuarios, el trabajo no tiene nada que hacer.
            // Se marca como fallido con un mensaje claro para el usuario.
            if ($users->isEmpty()) {
                $this->reportRecord->update([
                    'status' => 'failed',
                    'error_message' => 'No se encontraron docentes para generar los reportes. Verifique la configuración y que los usuarios tengan el "user_type" correcto.',
                ]);
                \Log::warning('GenerateReportsJob: No se encontraron usuarios. El trabajo ha fallado.');
                return; // Terminar el job aquí.
            }

            $logoUrl = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';
            $logoBase64 = '';
            $logoContent = @file_get_contents($logoUrl);
            if ($logoContent) {
                $logoBase64 = 'data:image/png;base64,' . base64_encode($logoContent);
            }

            $globalPeriod = \App\Models\UsersResponseForm1::calculateCurrentPeriod() ?? 
                            \App\Models\UsersResponseForm1::whereNotNull('periodo')->latest('updated_at')->value('periodo') ?? 
                            'SinPeriodo';
            $periodoArchivo = preg_replace('/[^A-Za-z0-9\-\_]/', '', str_replace(' ', '-', $globalPeriod));

            // 3. Generar PDFs individuales
            foreach ($users as $user) {
                $comisiones = DB::table('consolidated_responses')
                    ->where('user_id', $user->id)
                    ->orWhere('user_email', $user->email)
                    ->first() ?? (object)[];

                // Calcular totales y niveles dinámicamente (replicando lógica de ConsolidatedResponseController)
                // Esto asegura que los datos aparezcan aunque no se haya guardado el UserResume
                $s3_1 = ($comisiones->actv3Comision ?? 0) + ($comisiones->comision3_2 ?? 0) + ($comisiones->comision3_3 ?? 0) + ($comisiones->comision3_4 ?? 0) + ($comisiones->comision3_5 ?? 0) + ($comisiones->comision3_6 ?? 0) + ($comisiones->comision3_7 ?? 0) + ($comisiones->comision3_8 ?? 0) + ($comisiones->comision3_8_1 ?? 0);
                $s3_2 = ($comisiones->comision3_9 ?? 0) + ($comisiones->comision3_10 ?? 0) + ($comisiones->comision3_11 ?? 0);
                $s3_3 = ($comisiones->comision3_12 ?? 0) + ($comisiones->comision3_13 ?? 0) + ($comisiones->comision3_14 ?? 0) + ($comisiones->comision3_15 ?? 0) + ($comisiones->comision3_16 ?? 0);
                $s3_4 = ($comisiones->comision3_17 ?? 0) + ($comisiones->comision3_18 ?? 0) + ($comisiones->comision3_19 ?? 0);
                
                $totalCalidad = min($s3_1 + $s3_2 + $s3_3 + $s3_4, 700);
                $totalGeneral = min(($comisiones->comision1 ?? 0) + ($comisiones->actv2Comision ?? 0) + $totalCalidad, 1000);

                $minimaCalidad = $this->calculateLevelCalidad($totalCalidad);
                $minimaTotal = $this->calculateLevelTotal($totalGeneral);

                $form1 = \App\Models\UsersResponseForm1::where('user_id', $user->id)->first();
                $convocatoria = $form1->convocatoria ?? 'SinConvocatoria';
                $periodo = $form1->periodo ?? $globalPeriod;

                $dictaminadores = collect([]);
                if (method_exists($user, 'dictaminadores')) {
                    $dictaminadores = $user->dictaminadores()->with('dictaminadorSignature')->get()->map(function ($d) {
                        $comisionador = \App\Models\Comisionador::where('user_id', $d->id)->first();
                        $signature = $d->dictaminadorSignature;
                        $finalSignatureImage = ($comisionador && !empty($comisionador->firma_grafica)) ? $comisionador->firma_grafica : ($signature->signature_image ?? null);
                        return [
                            'name' => $signature->evaluator_name ?? $d->name,
                            'signature_image' => $finalSignatureImage,
                            'mime' => $signature->mime ?? 'image/png',
                        ];
                    })->unique('name')->values();
                }

                    $data = compact('user', 'logoBase64', 'comisiones', 'dictaminadores', 'convocatoria', 'periodo', 'minimaCalidad', 'minimaTotal');
                    $data['total'] = $totalCalidad; // Total de la sección 3 (Calidad)
                    $data['totalComisionRepetido'] = $totalGeneral; // Total Global

                $html = view('reporte_pdf', $data)->render();
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->render();

                $password = Str::random(10);
                $user->pdf_password = $password;
                $canvas = $dompdf->getCanvas();
                if ($canvas instanceof \Dompdf\Adapter\CPDF) {
                    $canvas->get_cpdf()->setEncryption($password, $password, ['print', 'copy']);
                }

                $safeNombre = Str::slug($user->name, '_') ?: 'Docente_' . $user->id;
                $pdfFilename = "{$periodoArchivo}_{$safeNombre}.pdf";
                file_put_contents($tempPath . '/' . $pdfFilename, $dompdf->output());
                $user->pdf_filename = $pdfFilename;
            }

            // 4. Generar Excel
            $excelFilename = 'Listado_Reportes.xlsx';
            Excel::store(new UsersExport($users), $tempDirName . '/' . $excelFilename);

            // 5. Crear archivo ZIP
            $zipFilename = 'reportes_pedpd_'.$periodoArchivo . '_' . $timestamp . '.zip';
            $zipPath = $reportsDir . '/' . $zipFilename;

            $zip = new ZipArchive;
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                $files = File::files($tempPath);
                foreach ($files as $file) {
                    $zip->addFile($file->getRealPath(), $file->getFilename());
                }
                $zip->close();
            }

            // 6. Actualizar registro en la base de datos
            $this->reportRecord->update([
                'status' => 'completed',
                'file_path' => $zipPath,
                'file_name' => $zipFilename,
            ]);

        } catch (Throwable $e) {
                // Si algo falla, registrar el error en los logs y en la base de datos.
                \Log::error('Error en GenerateReportsJob: ' . $e->getMessage(), ['exception' => $e]);
            $this->reportRecord->update([
                'status' => 'failed',
                    'error_message' => 'Error interno del servidor: ' . $e->getMessage() . ' en la línea ' . $e->getLine(),
            ]);
        } finally {
            // 7. Limpiar carpeta temporal
            if (File::exists($tempPath)) {
                File::deleteDirectory($tempPath);
            }
        }
    }

    // Funciones auxiliares para calcular el nivel (copiadas de la lógica del controlador)
    private function calculateLevelCalidad($total)
    {
        if ($total >= 650) return 'IX';
        if ($total >= 595) return 'VIII';
        if ($total >= 540) return 'VII';
        if ($total >= 485) return 'VI';
        if ($total >= 430) return 'V';
        if ($total >= 375) return 'IV';
        if ($total >= 320) return 'III';
        if ($total >= 265) return 'II';
        if ($total >= 210) return 'I';
        return 'N/A';
    }

    private function calculateLevelTotal($total)
    {
        if ($total >= 924) return 'IX';
        if ($total >= 846) return 'VIII';
        if ($total >= 768) return 'VII';
        if ($total >= 690) return 'VI';
        if ($total >= 612) return 'V';
        if ($total >= 534) return 'IV';
        if ($total >= 456) return 'III';
        if ($total >= 378) return 'II';
        if ($total >= 301) return 'I';
        return 'N/A';
    }
}
