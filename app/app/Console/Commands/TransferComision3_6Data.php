<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3_6Data extends Command
{
    protected $signature = 'transfer:comision3_6';
    protected $description = 'Transferir datos de comision3_6 de dictaminators_response_form3_6 a users_response_form3_6';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_6')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_6')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision3_6' => $dictaminator->comision3_6]);
        }

        $this->info('Datos de comision de la actividad 3_6, transferidos exitosamente.');
    }
}