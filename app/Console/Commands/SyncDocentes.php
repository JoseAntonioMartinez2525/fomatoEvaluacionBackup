<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Docente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SyncDocentes extends Command
{
    /**
     * El nombre y la firma del comando de consola.
     *
     * @var string
     */
    protected $signature = 'sync:docentes';

    /**
     * La descripción del comando de consola.
     *
     * @var string
     */
    protected $description = 'Sincroniza usuarios y docentes desde una fuente externa (API/Config)';

    /**
     * Ejecuta el comando de consola.
     */
    public function handle()
    {
        $this->info('Iniciando sincronización de docentes...');

        // 1. Obtener fechas de convocatoria para optimizar (evitar consultas repetitivas en el modelo)
        // Se usa la tabla 'evaluation_dates' que es la que usa el modelo Docente
        $evaluationDate = DB::table('evaluation_dates')
            ->where('type', 'docentes_llenado')
            ->orderBy('id', 'desc')
            ->first();

        $jsonFechas = null;
        if ($evaluationDate) {
            $jsonFechas = [
                'start_date' => $evaluationDate->start_date,
                'end_date' => $evaluationDate->end_date,
            ];
        } else {
            $this->warn('No se encontraron fechas en evaluation_dates para docentes_llenado.');
        }

        // Obtener periodo actual
        $periodo = \App\Models\UsersResponseForm1::calculateCurrentPeriod();

        // 2. Obtener datos (Simulación API usando el archivo de configuración config/docentes.php)
        $apiData = config('docentes', []);

        foreach ($apiData as $email => $data) {
            
            // Lógica de limpieza y separación de nombres (similar a SyncComisionadores)
            $nombreCompleto = $data['nombre'];
            // Remover títulos académicos comunes
            $nombreLimpio = preg_replace('/^(Dr\.|Dra\.|M\.C\.?|M\.S\.C\.?|Lic\.|Ing\.)\s+/i', '', $nombreCompleto);
            $partes = explode(' ', trim($nombreLimpio));
            
            $apellido2 = '';
            $apellido1 = '';
            $nombre = '';

            if (count($partes) > 2) {
                $apellido2 = array_pop($partes);
                $apellido1 = array_pop($partes);
                $nombre = implode(' ', $partes);
            } elseif (count($partes) == 2) {
                $apellido1 = array_pop($partes);
                $nombre = implode(' ', $partes);
            } else {
                $nombre = $nombreLimpio;
            }

            // 3. Crear o Actualizar Docente
            // NOTA: Tu modelo App\Models\Docente ya tiene un evento 'saved' en el método booted()
            // que se encarga automáticamente de buscar el email en la tabla 'users':
            // - Si existe: actualiza el usuario.
            // - Si no existe: crea el usuario nuevo.
            // Por lo tanto, solo necesitamos guardar el Docente aquí.

            $docenteData = [
                'nombre' => $nombre,
                'apellido_1' => $apellido1,
                'apellido_2' => $apellido2,
                'departamento' => $data['departamento'] ?? null,
                'area' => $data['area'] ?? null,
                'fecha_convocatoria' => $jsonFechas,
            ];

            if (Schema::hasColumn('docentes', 'periodo')) {
                $docenteData['periodo'] = $periodo;
            }
            
            Docente::updateOrCreate(
                ['email' => $email], // Condición de búsqueda única
                $docenteData
            );

            $this->info("Sincronizado: {$email}");
        }

        $this->info('Sincronización de docentes completada.');
    }
}
