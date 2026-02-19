<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SiaApiService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        // Lee la configuración o usa variables de entorno directamente
        $this->baseUrl = config('siaa.api_url', env('SIAA_API_URL', 'https://siia-develop.uabcs.mx'));
        $this->token = config('siaa.api_token', env('SIAA_API_TOKEN'));
    }

    /**
     * Obtiene la información de un usuario desde la API externa.
     *
     * @param string $email
     * @return array|null
     */
    public function getUserInfo(string $email): ?array
    {
        // --- CÓDIGO REAL DE LA API ---
        if (!$this->token) {
            if (app()->runningInConsole()) echo "\n[ERROR] Token no configurado.";
            \Log::error('SIAA API Token no está configurado.');
            return $this->getMockUserData($email);
        }

        try {
            $response = Http::withToken($this->token)
                ->acceptJson()
                ->withoutVerifying() // <-- AÑADE ESTA LÍNEA
                ->timeout(10) // Timeout explícito de 10 segundos
                ->get("{$this->baseUrl}/personal", [
                    'email' => $email
                ]);

            if ($response->successful()) {
                $data = $response->json();
                // Si la API devuelve una lista de resultados (array numérico), tomamos el primero
                if (isset($data[0]) && is_array($data[0])) {
                    return $data[0];
                }
                return $data; // Retorna los datos si ya es un objeto único
            }

            if (app()->runningInConsole()) {
                echo "\n[API ERROR {$response->status()}] {$email}: " . substr($response->body(), 0, 200);
            }

            \Log::error("Fallo en la petición a la API SIAA para el email: {$email}", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        } catch (\Exception $e) {
            if (app()->runningInConsole()) {
                echo "\n[API EXCEPTION] {$email}: " . $e->getMessage();
            }
            \Log::error("Error de conexión con API SIAA para {$email}: " . $e->getMessage());
        }

        // Fallback: Si la API falla, intenta usar los datos simulados/locales
        //return $this->getMockUserData($email);
    }

    /**
     * Provee datos de prueba mientras la API real no está integrada.
     *
     * @param string $email
     * @return array|null
     */
    private function getMockUserData(string $email): ?array
    {
        // Intenta obtener datos del archivo de configuración como fallback
        // Nota: No usamos config('key.email') porque los puntos en el email rompen la notación de array
        $dictaminadores = config('dictaminadores', []);
        $docentes = config('docentes', []);

        return $dictaminadores[$email] ?? $docentes[$email] ?? null;
    }

    /**
     * Busca dictaminadores en el Programa de Estímulos por nombre o apellidos.
     *
     * @param array $filters ['nombre' => '', 'primerApellido' => '', 'segundoApellido' => '']
     * @return array
     */
    public function searchDictaminadores(array $filters = []): array
    {
        if (!$this->token) {
            return [];
        }

        try {
            $response = Http::withToken($this->token)
                ->acceptJson()
                ->withoutVerifying()
                ->timeout(10)
                ->get("{$this->baseUrl}/ProgramaEstimulos/dictaminadores/search", $filters);

            if ($response->successful()) {
                return $response->json();
            }

            \Log::error("Fallo en búsqueda de dictaminadores: " . $response->status());
        } catch (\Exception $e) {
            \Log::error("Error de conexión en búsqueda de dictaminadores: " . $e->getMessage());
        }

        return [];
    }

    /**
     * Obtiene información detallada de un dictaminador por su ID de maestro.
     *
     * @param string $idMaestro
     * @return array|null
     */
    public function getDictaminadorById(string $idMaestro): ?array
    {
        if (!$this->token) {
            return null;
        }

        try {
            $response = Http::withToken($this->token)
                ->acceptJson()
                ->withoutVerifying()
                ->timeout(10)
                ->get("{$this->baseUrl}/ProgramaEstimulos/dictaminadores/{$idMaestro}");

            if ($response->successful()) {
                return $response->json();
            }

            \Log::error("Fallo al obtener dictaminador {$idMaestro}: " . $response->status());
        } catch (\Exception $e) {
            \Log::error("Error de conexión al obtener dictaminador {$idMaestro}: " . $e->getMessage());
        }

        return null;
    }
}
