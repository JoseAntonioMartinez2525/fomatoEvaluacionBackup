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
        // Usamos rtrim para asegurar que no haya doble slash al final
        $this->baseUrl = rtrim(config('siaa.api_url', env('SIAA_API_URL', 'https://siia-develop.uabcs.mx')), '/');
        $this->token = config('siaa.api_token', env('SIAA_API_TOKEN'));

        // Diagnóstico para consola: Verificar si el token se cargó
        if (app()->runningInConsole() && empty($this->token)) {
            $this->logConsole("⚠️ ADVERTENCIA: El token SIAA_API_TOKEN está vacío o nulo. Si usas config:cache, ejecuta 'php artisan config:clear'.");
        }
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
            $this->logError('Token no configurado en getUserInfo.');
            return $this->getMockUserData($email);
        }

        try {
            $response = Http::withToken($this->token)
                ->acceptJson()
                ->withHeaders(['User-Agent' => 'FormatoEvaluacion/1.0'])
                ->withoutVerifying() // <-- AÑADE ESTA LÍNEA
                ->timeout(30) // Aumentamos timeout
                ->get("{$this->baseUrl}/personal", [
                    'email' => $email
                ]);

            if ($response->successful()) {
                $data = $response->json();
                // Si la API devuelve una lista de resultados (array numérico), tomamos el primero
                if (isset($data[0]) && is_array($data[0])) {
                    return $data[0];
                }
                return is_array($data) ? $data : (array) $data; // Retorna los datos si ya es un objeto único
            }

            // Si la ruta /personal no existe (404) o no aporta datos, no logueamos el HTML completo.
            if ($response->status() === 404) {
                // Intentar fallback: buscar en el endpoint de dictaminadores y filtrar por email
                $this->logConsole("GET /personal returned 404 for {$email}, trying search fallback");
                $candidates = $this->searchDictaminadores(['primerApellido' => '%']);
                if (!empty($candidates) && is_array($candidates)) {
                    foreach ($candidates as $cand) {
                        if (!empty($cand['email']) && strtolower($cand['email']) === strtolower($email)) {
                            return $cand;
                        }
                    }
                }
                // si no se encontró, caerá al fallback de mock abajo
            }

            $this->logError("Fallo getUserInfo ({$response->status()}) para {$email}: " . substr($response->body(), 0, 100));
        } catch (\Exception $e) {
            $this->logError("Excepción getUserInfo {$email}: " . $e->getMessage());
        }

        // Fallback: Si la API falla, intenta usar los datos simulados/locales
        return $this->getMockUserData($email);
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
            $this->logError("Token no configurado. No se puede buscar dictaminadores.");
            return [];
        }

        // Forzamos la estructura de filtros para que siempre incluya los 3 campos,
        // que es lo que la API parece requerir para no devolver un 404.
        $finalFilters = [
            'nombre' => $filters['nombre'] ?? '',
            'primerApellido' => $filters['primerApellido'] ?? '',
            'segundoApellido' => $filters['segundoApellido'] ?? '',
        ];

        $url = "{$this->baseUrl}/ProgramaEstimulos/dictaminadores/search";
        $fullUrlForLog = $url . '?' . http_build_query($finalFilters);

        try {
            if (app()->runningInConsole()) {
                $this->logConsole("Consultando: GET $fullUrlForLog");
            }

            $response = Http::withToken($this->token)
                ->acceptJson()
                ->withHeaders(['User-Agent' => 'FormatoEvaluacion/1.0'])
                ->withoutVerifying()
                ->timeout(30)
                ->get($url, $finalFilters);

            if ($response->successful()) {
                $data = $response->json();
                if (is_array($data)) {
                    return $data;
                }
                if ($data === null) {
                    // Respuesta vacía (por ejemplo 204 No Content): devolver lista vacía
                    return [];
                }
                // Si la API devuelve un objeto asociativo, convertir a array
                return (array) $data;
            }

            $this->logError("Fallo búsqueda dictaminadores ({$response->status()}) en URL: {$fullUrlForLog}. Respuesta: " . substr($response->body(), 0, 200));
        } catch (\Exception $e) {
            $this->logError("Excepción búsqueda dictaminadores en URL: {$fullUrlForLog}. Error: " . $e->getMessage());
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
            $this->logError("Token no configurado. No se puede obtener dictaminador $idMaestro.");
            return null;
        }

        $url = "{$this->baseUrl}/ProgramaEstimulos/dictaminadores/{$idMaestro}";

        try {
            $response = Http::withToken($this->token)
                ->acceptJson()
                ->withHeaders(['User-Agent' => 'FormatoEvaluacion/1.0'])
                ->withoutVerifying()
                ->timeout(30)
                ->get($url);

            if ($response->successful()) {
                return $response->json();
            }

            $this->logError("Fallo obtener dictaminador $idMaestro ({$response->status()}): " . substr($response->body(), 0, 100));
        } catch (\Exception $e) {
            $this->logError("Excepción obtener dictaminador $idMaestro: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Helper para imprimir en consola si se está ejecutando un comando.
     */
    private function logConsole($message)
    {
        if (app()->runningInConsole()) {
            fwrite(STDERR, "  [API] $message\n");
        }
    }

    private function logError($message)
    {
        \Log::error("[SiaApiService] $message");
        $this->logConsole("ERROR: $message");
    }
}
