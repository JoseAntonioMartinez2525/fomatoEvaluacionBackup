<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\ExternalFormProvider;
use App\Models\Docente;
use App\Models\Comisionador;

class FetchProviderUsers extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'providers:fetch-users {provider : provider id or name} {0--dry-run : Do not persist changes} {--timeout=10 : HTTP timeout in seconds}';

    /**
     * The console command description.
     */
    protected $description = 'Fetch users from an ExternalFormProvider endpoint and import into Docente/Comisionador';

    public function handle()
    {
        $providerArg = $this->argument('provider');
        $dry = $this->option('dry-run');

        $provider = ExternalFormProvider::where('id', $providerArg)->orWhere('name', $providerArg)->first();
        if (!$provider) {
            $this->error('Provider not found: ' . $providerArg);
            return 1;
        }

        if (empty($provider->endpoint)) {
            $this->error('Provider has no endpoint set. Set `endpoint` on the provider record first.');
            return 1;
        }

        $timeout = (int) $this->option('timeout');
        $this->info('Fetching from: ' . $provider->endpoint . ' (timeout: ' . $timeout . 's)');

        try {
            $resp = Http::withToken($provider->token)
                ->timeout($timeout)
                ->get($provider->endpoint);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $this->error('Connection failed: ' . $e->getMessage());
            $this->error('Check network, endpoint URL, or increase `--timeout` and try again.');
            return 1;
        } catch (\Exception $e) {
            $this->error('HTTP request failed: ' . $e->getMessage());
            return 1;
        }

        if (!$resp->ok()) {
            $this->error('Request failed: ' . $resp->status());
            $this->line($resp->body());
            return 1;
        }

        $payload = $resp->json();

        // Normalize: if response has wrapper like ['users' => [...]]
        if (is_array($payload) && array_key_exists('users', $payload) && is_array($payload['users'])) {
            $users = $payload['users'];
        } elseif (is_array($payload) && array_is_list($payload)) {
            $users = $payload;
        } elseif (is_array($payload) && count($payload) > 0) {
            // Maybe associative containing objects keyed by id
            $users = array_values($payload);
        } else {
            $this->error('Unrecognized payload format from provider');
            return 1;
        }

        $this->info('Users found: ' . count($users));

        $docCount = 0;
        $comCount = 0;

        DB::beginTransaction();
        try {
            foreach ($users as $u) {
                if (!is_array($u)) continue;

                $role = $this->detectRole($u);
                if ($role === 'docente') {
                    $attrs = $this->mapToDocente($u);
                    if (empty($attrs['email'])) continue;
                    if (!$dry) {
                        Docente::updateOrCreate(['email' => $attrs['email']], $attrs);
                    }
                    $docCount++;
                } else {
                    $attrs = $this->mapToComisionador($u);
                    if (empty($attrs['email'])) continue;
                    if (!$dry) {
                        Comisionador::updateOrCreate(['email' => $attrs['email']], $attrs);
                    }
                    $comCount++;
                }
            }

            if ($dry) {
                DB::rollBack();
            } else {
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Import failed: ' . $e->getMessage());
            return 1;
        }

        $this->info('Imported docentes: ' . $docCount);
        $this->info('Imported comisionadores: ' . $comCount);

        return 0;
    }

    protected function detectRole(array $u)
    {
        $lower = $this->lowerKeys($u);
        $role = $lower['rol'] ?? $lower['role'] ?? ($lower['user_type'] ?? null);
        if (!$role) return 'comisionador';
        $role = strtolower($role);
        if (stripos($role, 'docente') !== false || stripos($role, 'teacher') !== false) return 'docente';
        if (stripos($role, 'dictamin') !== false || stripos($role, 'comision') !== false) return 'comisionador';
        // default to comisionador
        return 'comisionador';
    }

    protected function mapToDocente(array $item)
    {
        $out = [];
        $lower = $this->lowerKeys($item);
        $out['email'] = $lower['email'] ?? ($lower['mail'] ?? null);
        $out['nombre'] = $lower['nombre'] ?? ($lower['name'] ?? null);
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
        $out['departamento'] = $lower['departamento'] ?? ($lower['department'] ?? null);
        $out['area'] = $lower['area'] ?? null;
        if (isset($lower['fecha_convocatoria'])) $out['fecha_convocatoria'] = $lower['fecha_convocatoria'];
        if (isset($lower['periodo'])) $out['periodo'] = $lower['periodo'];
        return array_filter($out, function ($v) { return $v !== null && $v !== ''; });
    }

    protected function mapToComisionador(array $item)
    {
        $out = [];
        $lower = $this->lowerKeys($item);
        $out['email'] = $lower['email'] ?? ($lower['mail'] ?? null);
        $out['nombre'] = $lower['nombre'] ?? ($lower['name'] ?? null);
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
        $out['departamento'] = $lower['departamento'] ?? ($lower['department'] ?? null);
        $out['area'] = $lower['area'] ?? null;
        $out['id_maestro'] = $lower['id_maestro'] ?? ($lower['idmaestro'] ?? null) ?? ($lower['id'] ?? null);
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
