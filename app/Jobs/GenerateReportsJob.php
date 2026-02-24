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

                // Obtener datos de UserResume para mínimas de calidad y total
                $userResume = \App\Models\UserResume::where('user_id', $user->id)->first();
                $minimaCalidad = $userResume ? $userResume->minima_calidad : 'N/A';
                $minimaTotal = $userResume ? $userResume->minima_total : 'N/A';

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
                $data['total'] = $comisiones->total_puntaje ?? 0;

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
}
