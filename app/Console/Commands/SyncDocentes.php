<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SiaApiService;
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
     * La instancia del servicio de API.
     * @var SiaApiService
     */
    protected $apiService;

    /**
     * Ejecuta el comando de consola.
     */
    public function handle(SiaApiService $apiService)
    {
        $this->apiService = $apiService;
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

        // 2. Obtener lista de usuarios desde la API (Fuente de verdad)
        $this->info("Consultando API para obtener lista de docentes...");
        // La API requiere un filtro de búsqueda. Iteramos por el alfabeto para obtener todos los registros.
        $alphabet = range('A', 'Z');
        $allDocentes = [];

        $this->output->progressStart(count($alphabet));

        foreach ($alphabet as $letter) {
            $results = $this->apiService->searchDictaminadores(['primerApellido' => $letter]);
            if (!empty($results)) {
                foreach ($results as $result) {
                    if (isset($result['maestroId'])) {
                        $allDocentes[$result['maestroId']] = $result; // Usar ID como clave para evitar duplicados
                    }
                }
            }
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
        $docentesList = array_values($allDocentes);

        if (empty($docentesList)) {
            $this->warn('La API no devolvió ningún registro.');
            return;
        }

        $this->info("\nSe encontraron " . count($docentesList) . " registros únicos. Procesando...");

        $this->withProgressBar($docentesList, function ($apiData) use ($jsonFechas, $periodo) {
            $email = $apiData['email'] ?? null;
            if (!$email) return;

            // Datos básicos desde la lista de búsqueda
            $nombre = $apiData['nombre'] ?? '';
            $apellido1 = $apiData['primerApellido'] ?? '';
            $apellido2 = $apiData['segundoApellido'] ?? '';
            $departamento = $apiData['departamento'] ?? null;
            $idMaestro = $apiData['maestroId'] ?? null;
            $area = null;

            // Enriquecer datos usando el endpoint de dictaminadores si tenemos ID de maestro
            if ($idMaestro) {
                $dictaminadorInfo = $this->apiService->getDictaminadorById($idMaestro);
                if ($dictaminadorInfo) {
                    $area = $dictaminadorInfo['area'] ?? $area;
                    $departamento = $dictaminadorInfo['departamento'] ?? $departamento;
                }
            }

            $docenteData = [
                'nombre' => $nombre,
                'primerApellido' => $apellido1,
                'segundoApellido' => $apellido2,
                'departamento' => $departamento,
                'area' => $area,
                'fecha_convocatoria' => $jsonFechas,
                'maestroId' => $idMaestro,
            ];

            if (Schema::hasColumn('docentes', 'periodo')) {
                $docenteData['periodo'] = $periodo;
            }
            
            Docente::updateOrCreate(
                ['email' => $email], // Condición de búsqueda única
                $docenteData
            );
        });

        $this->info("\nSincronización de docentes completada.");
    }
}
