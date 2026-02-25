<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\DynamicFormResponse;
use App\Models\User;

class BackfillDynamicEvaluations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:backfill-dynamic-evaluations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rellena la tabla dictaminador_docente con las evaluaciones de formularios dinámicos que ya existen.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando la sincronización de evaluaciones dinámicas existentes...');

        // Obtenemos todas las respuestas de formularios dinámicos que tienen un evaluador asignado.
        $responses = DynamicFormResponse::whereNotNull('evaluador_id')
            ->with('form') // Cargamos la relación con el formulario para obtener el nombre
            ->whereNotNull('user_id')
            ->get();

        if ($responses->isEmpty()) {
            $this->info('No se encontraron evaluaciones dinámicas para sincronizar.');
            return 0;
        }

        $this->output->progressStart($responses->count());

        $insertedCount = 0;
        $updatedCount = 0;

        foreach ($responses as $response) {
            // Obtener el email del docente
            $docente = User::find($response->user_id);
            $docenteEmail = $docente ? $docente->email : null;

            // Usar el form_type (ej: "3.23") como identificador, con fallbacks
            $formIdentifier = $response->form ? ($response->form->form_type ?? $response->form->form_name) : ('dynamic_form_' . $response->dynamic_form_id);

            // Verificamos si el registro ya existe para contar las actualizaciones vs. inserciones
            $exists = DB::table('dictaminador_docente')->where([
                'docente_id' => $response->user_id,
                'dictaminador_id' => $response->evaluador_id,
                'form_type' => $formIdentifier, // Ahora usará "3.23" en lugar de "3.23 Convenios"
            ])->exists();

            if ($exists) {
                $updatedCount++;
            } else {
                $insertedCount++;
            }

            DB::table('dictaminador_docente')->updateOrInsert(
                [
                    'docente_id' => $response->user_id,
                    'dictaminador_id' => $response->evaluador_id,
                    'form_type' => $formIdentifier, // Ahora usará "3.23" en lugar de "3.23 Convenios"
                ],
                [
                    'docente_email' => $docenteEmail,
                    'created_at' => $response->created_at, // Usamos la fecha de la evaluación original
                    'updated_at' => $response->updated_at,
                ]
            );

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();

        $this->info("\nSincronización completada. Se insertaron {$insertedCount} nuevos registros y se actualizaron {$updatedCount} existentes.");

        return 0;
    }
}
