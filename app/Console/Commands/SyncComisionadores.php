<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SiaApiService;
use App\Models\User;
use App\Models\Comisionador;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SyncComisionadores extends Command
{
    /**
     * El nombre y la firma del comando de consola.
     *
     * @var string
     */
    protected $signature = 'sync:comisionadores';

    /**
     * La descripción del comando de consola.
     *
     * @var string
     */
    protected $description = 'Sincroniza usuarios y comisionadores desde una fuente externa (API)';

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
        $this->info('Iniciando sincronización de comisionadores...');

        // 1. Obtener fechas de convocatoria desde docentes_evaluation_dates
        $evaluationDate = DB::table('docentes_evaluation_dates')
            ->orderBy('id', 'desc')
            ->first();

        $jsonFechas = null;
        if ($evaluationDate) {
            $jsonFechas = [
                'start_date' => $evaluationDate->start_date,
                'end_date' => $evaluationDate->end_date,
            ];
        } else {
            $this->warn('No se encontraron fechas en docentes_evaluation_dates.');
        }

        // 2. Obtener lista de usuarios desde la API (Fuente de verdad)
        $this->info("Consultando API para obtener lista de dictaminadores...");
        // La API requiere un filtro de búsqueda. Iteramos por el alfabeto para obtener todos los registros.
        $alphabet = range('A', 'Z');
        $allDictaminadores = [];

        $this->output->progressStart(count($alphabet));

        foreach ($alphabet as $letter) {
            $results = $this->apiService->searchDictaminadores(['primerApellido' => $letter]);
            if (!empty($results)) {
                foreach ($results as $result) {
                    if (isset($result['maestroId'])) {
                        $allDictaminadores[$result['maestroId']] = $result; // Usar ID como clave para evitar duplicados
                    }
                }
            }
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
        $dictaminadoresList = array_values($allDictaminadores);

        if (empty($dictaminadoresList)) {
            $this->warn('La API no devolvió ningún dictaminador.');
            return;
        }

        $this->info("\nSe encontraron " . count($dictaminadoresList) . " registros únicos. Procesando...");

        $this->withProgressBar($dictaminadoresList, function ($apiData) use ($jsonFechas) {
            $email = $apiData['email'] ?? null;
            if (!$email) return;

            // Datos básicos desde la lista de búsqueda
            $nombre = $apiData['nombre'] ?? '';
            $apellido1 = $apiData['primerApellido'] ?? '';
            $apellido2 = $apiData['segundoApellido'] ?? '';
            $departamento = $apiData['departamento'] ?? null;
            $idMaestro = $apiData['maestroId'] ?? null;
            
            $area = null;
            $firmaGrafica = null;

            // Si tenemos el ID del maestro, consultamos el endpoint específico de dictaminadores
            // para obtener datos adicionales como la firma gráfica y el área específica.
            if ($idMaestro) {
                $dictaminadorInfo = $this->apiService->getDictaminadorById($idMaestro);
                if ($dictaminadorInfo) {
                    $firmaGrafica = $dictaminadorInfo['firma_gráfica'] ?? null;
                    $area = $dictaminadorInfo['area'] ?? $area;
                    $departamento = $dictaminadorInfo['departamento'] ?? $departamento;
                }
            }

            $fullName = trim("{$nombre} {$apellido1} {$apellido2}");

            // 3. Sincronizar con la tabla users
            $user = User::where('email', $email)->first();

            if ($user) {
                // Si existe, actualizamos datos informativos (departamento vendría de la API)
                $user->update([
                    'name' => $fullName,
                    'departamento' => $departamento,
                    'user_type' => 'dictaminador', // Asegurar rol
                ]);
            } else {
                // Si no existe, creamos el usuario
                $user = User::create([
                    'email' => $email,
                    'name' => $fullName,
                    'departamento' => $departamento,
                    'password' => Hash::make('password'), // Contraseña por defecto
                    'user_type' => 'dictaminador',
                ]);
            }

            // 4. Sincronizar con la tabla comisionadores
            Comisionador::updateOrCreate(
                ['email' => $email], // Condición de búsqueda
                [
                    'user_id' => $user->id,
                    'maestroId' => $idMaestro,
                    'nombre' => $nombre,
                    'primerApellido' => $apellido1,
                    'segundoApellido' => $apellido2,
                    'departamento' => $departamento,
                    'area' => $area,
                    'firma_grafica' => $firmaGrafica,
                    'fecha_convocatoria' => $jsonFechas, // JSON con start_date y end_date
                ]
            );
        });

        $this->info("\nSincronización completada.");
    }
}
