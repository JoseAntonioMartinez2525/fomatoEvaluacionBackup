<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3_11Data extends Command
{
    protected $signature = 'transfer:comision3_11';
    protected $description = 'Transferir datos de comision3_11 de dictaminators_response_form3_11 a users_response_form3_11';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_11')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_11')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision3_11' => $dictaminator->comision3_11]);
        }

        $this->info('Datos de comision de la actividad 3_11, transferidos exitosamente.');
    }
}