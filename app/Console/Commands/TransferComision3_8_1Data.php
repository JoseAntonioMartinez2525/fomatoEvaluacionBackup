<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3_8_1Data extends Command
{
    protected $signature = 'transfer:comision3_8_1';
    protected $description = 'Transferir datos de comision3_8_1 de dictaminators_response_form3_8_1 a users_response_form3_8_1';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_8_1')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_8_1')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision3_8_1' => $dictaminator->comision3_8_1]);
        }

        $this->info('Datos de comision de la actividad 3_8_1, transferidos exitosamente.');
    }
}