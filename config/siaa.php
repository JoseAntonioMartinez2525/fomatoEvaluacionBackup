<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración de la API Externa (SIAA)
    |--------------------------------------------------------------------------
    |
    | Aquí se almacenan las credenciales para conectarse a la API externa
    | que provee los datos de docentes y comisionadores.
    |
    */

    'api_url' => env('SIAA_API_URL', 'https://api.pedpd.com/v1'),
    'api_token' => env('SIAA_API_TOKEN'),
];
