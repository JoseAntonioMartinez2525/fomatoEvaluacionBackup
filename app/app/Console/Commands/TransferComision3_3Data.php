<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3_3Data extends Command
{
    protected $signature = 'transfer:comision3_3';
    protected $description = 'Transferir datos de comision3_3 de dictaminators_response_form3_3 a users_response_form3_3';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_3')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_3')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision3_3' => $dictaminator->comision3_3]);
        }

        $this->info('Datos de comision de la actividad 3_3, transferidos exitosamente.');
    }
}