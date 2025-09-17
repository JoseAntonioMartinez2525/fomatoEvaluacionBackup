<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3_19Data extends Command
{
    protected $signature = 'transfer:comision3_19';
    protected $description = 'Transferir datos de comision3_19 de dictaminators_response_form3_19 a users_response_form3_19';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_19')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_19')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision3_19' => $dictaminator->comision3_19]);
        }

        $this->info('Datos de comision de la actividad 3_19, transferidos exitosamente.');
    }
}