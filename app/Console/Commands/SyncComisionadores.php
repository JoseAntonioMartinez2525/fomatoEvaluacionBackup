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

        // 2. Obtener lista base de usuarios desde la configuración local (config/dictaminadores.php)
        // Este archivo actúa como fuente de verdad temporal para nombres y correos.
        $configDictaminadores = config('dictaminadores', []);
        $idCounter = 1;

        foreach (array_keys($configDictaminadores) as $email) {
            $this->info("Procesando: {$email}");

            // ---------------------------------------------------------------------------
            // PASO CLAVE: Consultar la API externa para obtener los datos del usuario
            // ---------------------------------------------------------------------------
            $apiData = $this->apiService->getUserInfo($email);

            if (!$apiData) {
                $this->warn(" -> No se encontraron datos en la API para {$email}. Saltando...");
                continue;
            }

            $nombreCompleto = $apiData['nombre'] ?? '';
            // Remover títulos comunes (Dr., Dra., M.C., etc.)
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

            // Extraer datos desde la respuesta de la API
            $departamento = $apiData['departamento'] ?? null;
            $area = $apiData['area'] ?? null;
            $firmaGrafica = null; // Vendrá de la API (base64)
            $idMaestro = $apiData['id_maestro'] ?? null;

            $fullName = trim("{$nombre} {$apellido1} {$apellido2}");

            // 3. Sincronizar con la tabla users
            $user = User::where('email', $email)->first();

            if ($user) {
                // Si existe, actualizamos datos informativos (departamento vendría de la API)
                $user->update([
                    'name' => $fullName,
                    'departamento' => $departamento,
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
                    'id_maestro' => $idMaestro,
                    'nombre' => $nombre,
                    'apellido_1' => $apellido1,
                    'apellido_2' => $apellido2,
                    'departamento' => $departamento,
                    'area' => $area,
                    'firma_grafica' => $firmaGrafica,
                    'fecha_convocatoria' => $jsonFechas, // JSON con start_date y end_date
                ]
            );

            $this->line(" -> Sincronizado: {$fullName} (ID Maestro: {$idMaestro})");
        }

        $this->info('Sincronización completada.');
    }
}
