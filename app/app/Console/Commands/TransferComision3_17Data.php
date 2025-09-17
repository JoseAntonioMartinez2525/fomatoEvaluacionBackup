<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3_17Data extends Command
{
    protected $signature = 'transfer:comision3_17';
    protected $description = 'Transferir datos de comision3_17 de dictaminators_response_form3_17 a users_response_form3_17';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_17')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_17')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision3_17' => $dictaminator->comision3_17]);
        }

        $this->info('Datos de comision de la actividad 3_17, transferidos exitosamente.');
    }
}