<?php

namespace App\Console\Commands;

use App\Models\DynamicForm;
use Illuminate\Console\Command;

class NormalizeDynamicFormStructure extends Command
{
    protected $signature = 'app:normalize-dynamic-form-structure';

    protected $description = 'Normaliza form_structure agregando group a columnas antiguas';

    public function handle()
    {
        $this->info('Normalizando form_structure...');

        foreach (DynamicForm::all() as $form) {

            // 1. Asegurar array
            $structure = $form->form_structure;

            if (is_string($structure)) {
                $structure = json_decode($structure, true) ?? [];
            }

            if (!is_array($structure)) {
                continue;
            }

            $changed = false;

            foreach ($structure as &$col) {
                if (!isset($col['group'])) {

                    $key = $col['key'] ?? '';

                    if ($key === 'actividad') {
                        $col['group'] = 'actividad';

                    } elseif ($key === 'puntaje_a_evaluar') {
                        $col['group'] = 'Puntaje a evaluar';

                    } elseif (str_contains($key, 'Puntaje de la comisión dictamninadora')) {
                        $col['group'] = 'Puntaje de la comisión dictamninadora';

                    } elseif ($key === 'observaciones') {
                        $col['group'] = 'observaciones';

                    } else {
                        // Columnas dinámicas antiguas
                        $col['group'] = 'actividad';
                    }

                    $changed = true;
                }
            }

            if ($changed) {
                $form->form_structure = $structure;
                $form->save();

                $this->line("✔ Formulario {$form->id} normalizado");
            }
        }

        $this->info('Normalización completada.');
    }
}
