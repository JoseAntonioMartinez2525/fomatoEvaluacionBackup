<?php

namespace App\Support;

class DictaminadoresConfig
{
    protected static function raw(): array
    {
        return config('dictaminadores', []);
    }

    /**
     * Normaliza a una estructura uniforme
     * [
     *   [
     *     'email' => '',
     *     'nombre' => '',
     *     'departamento' => ''
     *   ]
     * ]
     */
    public static function all(): array
    {
        $out = [];

        foreach (self::raw() as $email => $data) {
            $out[] = [
                'email'        => $email,
                'nombre'       => $data['nombre'] ?? null,
                'departamento' => $data['departamento'] ?? null,
            ];
        }

        return $out;
    }

    /** SOLO correos */
    public static function emails(): array
    {
        return array_keys(self::raw());
    }

    /** Buscar por correo */
    public static function byEmail(string $email): ?array
    {
        $data = self::raw()[strtolower($email)] ?? null;

        return $data ? array_merge(['email' => $email], $data) : null;
    }
}
