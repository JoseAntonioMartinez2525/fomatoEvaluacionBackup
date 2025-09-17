<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3_4Data extends Command
{
    protected $signature = 'transfer:comision3_4';
    protected $description = 'Transferir datos de comision3_4 de dictaminators_response_form3_4 a users_response_form3_4';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_4')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_4')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision3_4' => $dictaminator->comision3_4]);
        }

        $this->info('Datos de comision de la actividad 3_4, transferidos exitosamente.');
    }
}