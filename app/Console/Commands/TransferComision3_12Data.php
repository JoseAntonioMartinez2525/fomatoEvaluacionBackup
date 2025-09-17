<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3_12Data extends Command
{
    protected $signature = 'transfer:comision3_12';
    protected $description = 'Transferir datos de comision3_12 de dictaminators_response_form3_12 a users_response_form3_12';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_12')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_12')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision3_12' => $dictaminator->comision3_12]);
        }

        $this->info('Datos de comision de la actividad 3_12, transferidos exitosamente.');
    }
}