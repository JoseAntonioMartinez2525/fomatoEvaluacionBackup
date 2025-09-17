<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3_16Data extends Command
{
    protected $signature = 'transfer:comision3_16';
    protected $description = 'Transferir datos de comision3_16 de dictaminators_response_form3_16 a users_response_form3_16';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_16')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_16')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision3_16' => $dictaminator->comision3_16]);
        }

        $this->info('Datos de comision de la actividad 3_16, transferidos exitosamente.');
    }
}