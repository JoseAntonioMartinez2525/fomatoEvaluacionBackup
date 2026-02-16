<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Docente;
use App\Models\Comisionador;

class ImportFormatos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Accepts a path to a JSON file (absolute or relative).
     */
    protected $signature = 'import:formatos {path : Path to JSON file}';

    /**
     * The console command description.
     */
    protected $description = 'Import docentes and comisionadores from a JSON file into DB';

    public function handle()
    {
        $path = $this->argument('path');

        if (!file_exists($path)) {
            $this->error("File not found: {$path}");
            return 1;
        }

        $json = file_get_contents($path);
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Invalid JSON: ' . json_last_error_msg());
            return 1;
        }

        $this->info('Processing JSON...');

        $docenteCount = 0;
        $comisionadorCount = 0;

        DB::beginTransaction();
        try {
            // First try to find explicit arrays
            $docentes = [];
            $comisionadores = [];

            $keys = is_array($data) ? array_keys($data) : [];

            foreach ($keys as $k) {
                $lk = strtolower($k);
                if (strpos($lk, 'docente') !== false || strpos($lk, 'docentes') !== false) {
                    $docentes = array_merge($docentes, (array)$data[$k]);
                }
                if (strpos($lk, 'dictaminador') !== false || strpos($lk, 'comisionador') !== false || strpos($lk, 'comisionadores') !== false) {
                    $comisionadores = array_merge($comisionadores, (array)$data[$k]);
                }
            }

            // If none found, recursively search for person-like objects
            if (empty($docentes) && empty($comisionadores)) {
                $this->info('No explicit docente/comisionador arrays found. Scanning for person records...');
                $found = $this->findPersonsRecursive($data);
                foreach ($found as $person) {
                    if ($this->looksLikeComisionador($person)) {
                        $comisionadores[] = $person;
                    } else {
                        $docentes[] = $person;
                    }
                }
            }

            // Process docentes
            foreach ($docentes as $item) {
                $attrs = $this->mapToDocente($item);
                if (empty($attrs['email'])) continue;
                $docente = Docente::updateOrCreate(
                    ['email' => $attrs['email']],
                    $attrs
                );
                $docenteCount++;
            }

            // Process comisionadores
            foreach ($comisionadores as $item) {
                $attrs = $this->mapToComisionador($item);
                if (empty($attrs['email'])) continue;
                $com = Comisionador::updateOrCreate(
                    ['email' => $attrs['email']],
                    $attrs
                );
                $comisionadorCount++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Import failed: ' . $e->getMessage());
            return 1;
        }

        $this->info("Imported docentes: {$docenteCount}");
        $this->info("Imported comisionadores: {$comisionadorCount}");

        return 0;
    }

    protected function findPersonsRecursive($data)
    {
        $results = [];
        if (is_array($data)) {
            // If this array looks like a list of persons
            $isList = $this->arrayLooksLikeListOfPersons($data);
            if ($isList) {
                foreach ($data as $item) {
                    if (is_array($item) && $this->isPersonObject($item)) {
                        $results[] = $item;
                    }
                }
                if (!empty($results)) return $results;
            }

            foreach ($data as $v) {
                if (is_array($v)) {
                    $results = array_merge($results, $this->findPersonsRecursive($v));
                }
            }
        }
        return $results;
    }

    protected function arrayLooksLikeListOfPersons(array $arr)
    {
        if (empty($arr)) return false;
        $count = 0;
        $check = 0;
        foreach ($arr as $item) {
            if (!is_array($item)) return false;
            $check++;
            if ($this->isPersonObject($item)) $count++;
        }
        return $check > 0 && $count / $check >= 0.5;
    }

    protected function isPersonObject(array $obj)
    {
        $keys = array_map('strtolower', array_keys($obj));
        return in_array('email', $keys) && (in_array('nombre', $keys) || in_array('name', $keys) || in_array('nombres', $keys));
    }

    protected function looksLikeComisionador(array $obj)
    {
        $keys = array_map('strtolower', array_keys($obj));
        if (isset($obj['user_type']) && strtolower($obj['user_type']) === 'dictaminador') return true;
        if (isset($obj['tipo']) && stripos($obj['tipo'], 'dictamin') !== false) return true;
        foreach (['id_maestro', 'idMaestro', 'firma', 'firma_grafica'] as $k) {
            if (array_key_exists($k, $obj)) return true;
        }
        return false;
    }

    protected function mapToDocente(array $item)
    {
        $out = [];
        $lower = $this->lowerKeys($item);
        $out['email'] = $lower['email'] ?? null;
        if (isset($lower['nombre'])) $out['nombre'] = $lower['nombre'];
        elseif (isset($lower['name'])) $out['nombre'] = $lower['name'];
        if (isset($lower['apellido_1'])) $out['apellido_1'] = $lower['apellido_1'];
        elseif (isset($lower['apellido1'])) $out['apellido_1'] = $lower['apellido1'] ?? null;
        if (isset($lower['apellido_2'])) $out['apellido_2'] = $lower['apellido_2'];
        elseif (isset($lower['apellido2'])) $out['apellido_2'] = $lower['apellido2'] ?? null;
        if (empty($out['apellido_1']) && empty($out['apellido_2']) && !empty($out['nombre'])) {
            // Try to split full name
            $parts = preg_split('/\s+/', $out['nombre']);
            if (count($parts) >= 3) {
                $out['nombre'] = array_shift($parts);
                $out['apellido_1'] = array_shift($parts);
                $out['apellido_2'] = implode(' ', $parts);
            }
        }
        $out['departamento'] = $lower['departamento'] ?? $lower['department'] ?? null;
        $out['area'] = $lower['area'] ?? null;
        if (isset($lower['fecha_convocatoria'])) {
            $out['fecha_convocatoria'] = $lower['fecha_convocatoria'];
        }
        if (isset($lower['periodo'])) $out['periodo'] = $lower['periodo'];
        return array_filter($out, function ($v) { return $v !== null && $v !== ''; });
    }

    protected function mapToComisionador(array $item)
    {
        $out = [];
        $lower = $this->lowerKeys($item);
        $out['email'] = $lower['email'] ?? null;
        if (isset($lower['nombre'])) $out['nombre'] = $lower['nombre'];
        elseif (isset($lower['name'])) $out['nombre'] = $lower['name'];
        $out['apellido_1'] = $lower['apellido_1'] ?? ($lower['apellido1'] ?? null);
        $out['apellido_2'] = $lower['apellido_2'] ?? ($lower['apellido2'] ?? null);
        if (empty($out['apellido_1']) && empty($out['apellido_2']) && !empty($out['nombre'])) {
            $parts = preg_split('/\s+/', $out['nombre']);
            if (count($parts) >= 3) {
                $out['nombre'] = array_shift($parts);
                $out['apellido_1'] = array_shift($parts);
                $out['apellido_2'] = implode(' ', $parts);
            }
        }
        $out['departamento'] = $lower['departamento'] ?? $lower['department'] ?? null;
        $out['area'] = $lower['area'] ?? null;
        $out['id_maestro'] = $lower['id_maestro'] ?? ($lower['idmaestro'] ?? null);
        $out['firma_grafica'] = $lower['firma_grafica'] ?? ($lower['firma'] ?? null);
        if (isset($lower['fecha_convocatoria'])) $out['fecha_convocatoria'] = $lower['fecha_convocatoria'];
        return array_filter($out, function ($v) { return $v !== null && $v !== ''; });
    }

    protected function lowerKeys(array $arr)
    {
        $out = [];
        foreach ($arr as $k => $v) {
            $out[strtolower($k)] = $v;
        }
        return $out;
    }
}
