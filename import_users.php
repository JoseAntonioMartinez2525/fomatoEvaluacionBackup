<?php

// Usage: php import_users.php /path/to/formatos.json [--dry-run]

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Str;
use App\Models\Docente;
use App\Models\Comisionador;
use App\Services\SiaApiService;

$argc = $_SERVER['argc'];
$argv = $_SERVER['argv'];
if ($argc < 2) {
    echo "Usage: php import_users.php /path/to/formatos.json [--dry-run]\n";
    exit(1);
}

$path = $argv[1];
$dry = in_array('--dry-run', $argv, true);

if (!file_exists($path)) {
    echo "File not found: $path\n";
    exit(1);
}

$body = file_get_contents($path);
$payload = json_decode($body, true);
if ($payload === null) {
    echo "Invalid JSON in file: $path\n";
    exit(1);
}

// normalize possible wrappers
if (is_array($payload) && array_key_exists('users', $payload) && is_array($payload['users'])) {
    $users = $payload['users'];
} elseif (is_array($payload) && array_values($payload) === $payload) {
    $users = $payload;
} else {
    $users = array_values($payload);
}

echo "Users found: " . count($users) . "\n";

// Instanciar el servicio de API
$apiService = $app->make(SiaApiService::class);

$docCount = 0;
$comCount = 0;

foreach ($users as $u) {
    if (!is_array($u)) continue;

    // helper: recursively find possible keys
    $find = function ($arr, $keys) use (&$find) {
        if (!is_array($arr)) return null;
        $lower = [];
        foreach ($arr as $k => $v) $lower[strtolower($k)] = $v;
        foreach ($keys as $k) {
            if (array_key_exists($k, $lower) && $lower[$k] !== null && $lower[$k] !== '') return $lower[$k];
        }
        // search nested arrays/objects
        foreach ($lower as $v) {
            if (is_array($v)) {
                $res = $find($v, $keys);
                if ($res !== null) return $res;
            }
        }
        return null;
    };

    $email = $find($u, ['email','mail','correo','correo_electronico','correoelectronico','emailaddress','username','user']);
    if (!$email) continue;

    // role detection: look for single role or roles array
    $roleVal = $find($u, ['rol','role','user_type','tipo','roles']);
    $rl = '';
    if (is_array($roleVal)) {
        // array of roles: join or pick first
        $flat = [];
        array_walk_recursive($roleVal, function($v) use (&$flat){ if (is_string($v)) $flat[] = $v; });
        $rl = implode(' ', $flat);
    } else {
        $rl = (string) $roleVal;
    }
    $rl = strtolower($rl);
    $isDoc = (stripos($rl, 'docente') !== false) || (stripos($rl, 'teacher') !== false);
    $isDict = (stripos($rl, 'dictamin') !== false);

    if ($isDoc) {
        $attrs = [
            'email' => $email,
            'nombre' => $find($u, ['nombre','name','given_name','first_name']),
            'primerApellido' => $find($u, ['primerApellido','apellido1','last_name','apellido']),
            'segundoApellido' => $find($u, ['segundoApellido','apellido2','second_last_name']),
            'departamento' => $find($u, ['departamento','department','area']),
            'maestroId' => $find($u, ['maestroId','idmaestro','id','teacher_id','maestro_id']),
        ];
        $attrs = array_filter($attrs, fn($v) => $v !== null && $v !== '');

        if (!empty($attrs['maestroId'])) {
            $extra = $apiService->getDictaminadorById($attrs['maestroId']);
            if ($extra) {
                $attrs['area'] = $extra['area'] ?? ($attrs['area'] ?? null);
                $attrs['departamento'] = $extra['departamento'] ?? ($attrs['departamento'] ?? null);
            }
        }

        echo "Docente: $email -> " . json_encode($attrs) . PHP_EOL;
        if (!$dry) {
            Docente::updateOrCreate(['email' => $email], $attrs);
        }
        $docCount++;
    } else {
        $attrs = [
            'email' => $email,
            'nombre' => $find($u, ['nombre','name','given_name','first_name']),
            'primerApellido' => $find($u, ['primerApellido','apellido1','last_name','apellido']),
            'segundoApellido' => $find($u, ['segundoApellido','apellido2','second_last_name']),
            'departamento' => $find($u, ['departamento','department','area']),
            'maestroId' => $find($u, ['maestroId','idmaestro','id','teacher_id','maestro_id']),
        ];
        $attrs = array_filter($attrs, fn($v) => $v !== null && $v !== '');

        if (!empty($attrs['maestroId'])) {
            $extra = $apiService->getDictaminadorById($attrs['maestroId']);
            if ($extra) {
                $attrs['area'] = $extra['area'] ?? ($attrs['area'] ?? null);
                $attrs['departamento'] = $extra['departamento'] ?? ($attrs['departamento'] ?? null);
                $attrs['firma_grafica'] = $extra['firma_gráfica'] ?? ($attrs['firma_grafica'] ?? null);
            }
        }

        echo "Comisionador: $email -> " . json_encode($attrs) . PHP_EOL;
        if (!$dry) {
            Comisionador::updateOrCreate(['email' => $email], $attrs);
        }
        $comCount++;
    }
}

echo "Processed docentes: $docCount, comisionadores: $comCount\n";
if ($dry) echo "(dry-run, no DB changes were made)\n";
