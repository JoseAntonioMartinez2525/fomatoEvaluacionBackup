<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3_10Data extends Command
{
    protected $signature = 'transfer:comision3_10';
    protected $description = 'Transferir datos de comision3_10 de dictaminators_response_form3_10 a users_response_form3_10';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_10')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_10')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision3_10' => $dictaminator->comision3_10]);
        }

        $this->info('Datos de comision de la actividad 3_10, transferidos exitosamente.');
    }
}