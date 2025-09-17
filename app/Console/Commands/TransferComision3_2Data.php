<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3_2Data extends Command
{
    protected $signature = 'transfer:comision3_2';
    protected $description = 'Transferir datos de comision3_2 de dictaminators_response_form3_2 a users_response_form3_2';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_2')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_2')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision3_2' => $dictaminator->comision3_2]);
        }

        $this->info('Datos de comision de la actividad 3.2, transferidos exitosamente.');
    }
}