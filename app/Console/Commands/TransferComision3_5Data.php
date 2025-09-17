<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3_5Data extends Command
{
    protected $signature = 'transfer:comision3_5';
    protected $description = 'Transferir datos de comision3_5 de dictaminators_response_form3_5 a users_response_form3_5';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_5')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_5')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision3_5' => $dictaminator->comision3_5]);
        }

        $this->info('Datos de comision de la actividad 3_5, transferidos exitosamente.');
    }
}