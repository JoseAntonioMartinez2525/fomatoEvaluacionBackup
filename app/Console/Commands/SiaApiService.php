<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SiaApiService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        // Lee la configuración desde un nuevo archivo config/siaa.php
        $this->baseUrl = config('siaa.api_url');
        $this->token = config('siaa.api_token');
    }

    /**
     * Obtiene la información de un usuario desde la API externa.
     *
     * @param string $email
     * @return array|null
     */
    public function getUserInfo(string $email): ?array
    {
        // --- CÓDIGO REAL DE LA API (Activar cuando esté lista) ---
        /*
        if (!$this->token) {
            \Log::error('SIAA API Token no está configurado.');
            return null;
        }

        $response = Http::withToken($this->token)
            ->acceptJson()
            ->get("{$this->baseUrl}/personal/{$email}"); // Asegúrate que la URL del endpoint es correcta

        if ($response->successful()) {
            return $response->json(); // Retorna los datos del usuario
        }

        \Log::error("Fallo en la petición a la API SIAA para el email: {$email}", [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        return null;
        */

        // --- RESPUESTA SIMULADA (para desarrollo) ---
        // Esto simula la obtención de datos para un usuario.
        // Reemplaza esto con la llamada real a la API de arriba.
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
        // Datos de ejemplo para simular la respuesta de la API por cada usuario
        $users = [
            'bramirez@uabcs.mx' => ['id_maestro' => 1001, 'rol' => 'dictaminador', 'area' => 'Ciencias Jurídicas', 'departamento' => 'Ciencias Sociales y Juridicas', 'nombre' => 'Dra. Brenda Elizabeth Ramírez Díaz'],
            'jperez@uabcs.mx' => ['id_maestro' => 1002, 'rol' => 'dictaminador', 'area' => 'Ciencias Jurídicas', 'departamento' => 'Ciencias Sociales y Juridicas', 'nombre' => 'M.C Juan Carlos Pérez Concha'],
            'sandoval@uabcs.mx' => ['id_maestro' => 1003, 'rol' => 'dictaminador', 'area' => 'Tecnologías de la Información', 'departamento' => 'Sistemas Computacionales', 'nombre' => 'Dr. Jesús Andrés Sandoval Bringas'],
            // ... puedes agregar más usuarios de prueba aquí
        ];

        if (array_key_exists($email, $users)) {
            return $users[$email];
        }

        // Intenta obtener datos del archivo de configuración como fallback
        return config('dictaminadores.'.$email) ?? config('docentes.'.$email, null);
    }
}
