<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3_15Data extends Command
{
    protected $signature = 'transfer:comision3_15';
    protected $description = 'Transferir datos de comision3_15 de dictaminators_response_form3_15 a users_response_form3_15';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_15')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_15')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision3_15' => $dictaminator->comision3_15]);
        }

        $this->info('Datos de comision de la actividad 3_15, transferidos exitosamente.');
    }
}