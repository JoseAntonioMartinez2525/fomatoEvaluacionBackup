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
        $this->info("Consultando API para obtener datos de los dictaminadores listados en config/dictaminadores.php...");

        // Lista blanca de correos (emails) desde config
        $whitelistEmails = array_map('strtolower', array_keys(config('dictaminadores', [])));
        if (empty($whitelistEmails)) {
            $this->warn('No hay correos definidos en config/dictaminadores.php. Nada que sincronizar.');
            return;
        }

        // Preparar mapa de config para usar nombres como fuente de verdad cuando existan
        $configMap = array_change_key_case(config('dictaminadores', []), CASE_LOWER);

        $this->output->progressStart(count($whitelistEmails));

        $allDictaminadores = [];

        foreach ($whitelistEmails as $email) {
            $result = null;

            // Si en config hay un nombre completo para este email, intentar buscar por nombre y apellidos
            $cfg = $configMap[strtolower($email)] ?? null;
            if (!empty($cfg) && !empty($cfg['nombre'])) {
                // If config already provides split last names, use them directly
                if (!empty($cfg['primerApellido']) || !empty($cfg['segundoApellido'])) {
                    $given = $cfg['nombre'];
                    $pApellido = $cfg['primerApellido'] ?? '';
                    $sApellido = $cfg['segundoApellido'] ?? '';
                } else {
                    // Limpiar prefijos honoríficos y títulos comunes
                    $nameRaw = preg_replace('/\b(Dr|Dra|Dra\.|Dr\.|M\.S\.C\.?|M\.C\.?|MSc|Lic|Ing)\b/i', '', $cfg['nombre']);
                    $nameRaw = trim($nameRaw);

                    // Intentar remover acentos para mejorar coincidencias (transliterate)
                    $nameNorm = @iconv('UTF-8', 'ASCII//TRANSLIT', $nameRaw) ?: $nameRaw;

                    // Split into parts: assume first is given names, last two are surnames
                    $parts = preg_split('/\s+/', $nameNorm);
                    $given = '';
                    $pApellido = '';
                    $sApellido = '';
                    if (count($parts) >= 3) {
                        $given = $parts[0] . (isset($parts[1]) ? ' ' . $parts[1] : '');
                        $pApellido = $parts[count($parts)-2];
                        $sApellido = $parts[count($parts)-1];
                    } elseif (count($parts) === 2) {
                        $given = $parts[0];
                        $pApellido = $parts[1];
                    } else {
                        $given = $parts[0] ?? '';
                    }
                }

                // Hacer búsqueda específica por nombre y apellidos
                $searchFilters = [
                    'nombre' => $given,
                    'primerApellido' => $pApellido,
                    'segundoApellido' => $sApellido,
                ];

                $this->line('  [API] Buscando en API por nombre/apellidos: ' . json_encode($searchFilters));
                $candidates = $this->apiService->searchDictaminadores($searchFilters);
                if (!empty($candidates) && is_array($candidates)) {
                    // Preferir candidato con email que coincida
                    foreach ($candidates as $cand) {
                        if (!empty($cand['email']) && strtolower($cand['email']) === strtolower($email)) {
                            $result = $cand;
                            break;
                        }
                    }
                    // Si no encontramos por email, tomar el primer candidato
                    if (empty($result)) {
                        $result = $candidates[0];
                    }
                }
            }

            // Si no obtuvimos resultado por nombre/config, intentar obtener por email (getUserInfo)
            if (empty($result)) {
                $result = $this->apiService->getUserInfo($email);
                if ($result && !is_array($result)) $result = (array) $result;
            }

            // Si aún no hay resultado, usar búsqueda amplia por primer apellido como último recurso
            if (empty($result)) {
                $candidates = $this->apiService->searchDictaminadores(['primerApellido' => '%']);
                if (!empty($candidates) && is_array($candidates)) {
                    foreach ($candidates as $cand) {
                        if (!empty($cand['email']) && strtolower($cand['email']) === strtolower($email)) {
                            $result = $cand;
                            break;
                        }
                    }
                }
            }

            if (!empty($result)) {
                $key = $result['maestroId'] ?? $email;
                $allDictaminadores[$key] = $result;
            } else {
                // Registrar advertencia si no se encontró en la API
                $this->warn("No se encontró información en la API para: {$email}");
            }

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();

        $dictaminadoresList = array_values($allDictaminadores);

        if (empty($dictaminadoresList)) {
            $this->warn('No se obtuvo información de la API para ninguno de los correos listados.');
            return;
        }

        // Normalizar map de configuración para lookup por email (case-insensitive)
        $configMap = array_change_key_case(config('dictaminadores', []), CASE_LOWER);

        $this->info("\nSe procesarán " . count($dictaminadoresList) . " dictaminadores (según config/dictaminadores.php).");

        // Obtener la lista blanca de correos desde config/dictaminadores.php
        $whitelist = array_map('strtolower', array_keys(config('dictaminadores', [])));
        if (empty($whitelist)) {
            $this->warn('La lista de dictaminadores en config/dictaminadores.php está vacía. No se aplicará filtro.');
        }

        $this->withProgressBar($dictaminadoresList, function ($apiData) use ($jsonFechas, $whitelist, $configMap) {
            $email = $apiData['email'] ?? null;
            if (!$email) return;

            // Si hay whitelist definida, filtrar los emails que NO estén en la lista
            if (!empty($whitelist) && !in_array(strtolower($email), $whitelist)) {
                // No está en la lista, ignorar
                return;
            }

            // Datos básicos desde la lista de búsqueda (preferir API, usar config solo como fallback)
            $nombre = $apiData['nombre'] ?? '';
            $apellido1 = $apiData['primerApellido'] ?? '';
            $apellido2 = $apiData['segundoApellido'] ?? '';
            $departamento = $apiData['departamento'] ?? null;

            // Lookup config entry for this email (case-insensitive)
            $cfg = $configMap[strtolower($email)] ?? null;
            if ($cfg) {
                // Si la API no entregó nombre/apellidos, intentar parsear el nombre completo de la config
                if (empty($nombre) && !empty($cfg['nombre'])) {
                    $parts = preg_split('/\s+/', $cfg['nombre']);
                    if (count($parts) >= 3) {
                        $nombre = array_shift($parts);
                        $apellido1 = array_shift($parts);
                        $apellido2 = implode(' ', $parts);
                    } else {
                        // Fallback simple
                        $nombre = $cfg['nombre'];
                    }
                }

                // Si no hay departamento desde la API, usar el de la config
                if (empty($departamento) && !empty($cfg['departamento'])) {
                    $departamento = $cfg['departamento'];
                }
            }
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
