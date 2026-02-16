<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExternalFormProvider;

class ImportProvider extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'providers:import {path : Path to provider JSON metadata}';

    /**
     * The console command description.
     */
    protected $description = 'Import external form provider metadata (name, token, meta) into DB';

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

        $name = $data['name'] ?? ($data['id'] ?? null);
        if (!$name) {
            $this->error('Provider name/id not found in metadata');
            return 1;
        }

        $record = ExternalFormProvider::updateOrCreate(
            ['name' => $name],
            [
                'description' => $data['description'] ?? null,
                'token' => $data['token'] ?? null,
                'meta' => $data,
            ]
        );

        $this->info("Provider registered: {$record->name} (id: {$record->id})");
        $this->info('You can later set an `endpoint` for this provider and create a fetcher to import users.');

        return 0;
    }
}
