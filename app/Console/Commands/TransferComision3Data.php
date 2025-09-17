<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3Data extends Command
{
    protected $signature = 'transfer:actv3Comision';
    protected $description = 'Transferir datos de comision2 de dictaminators_response_form3_1 a users_response_form3_1';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_1')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_1')
                ->where('user_id', $dictaminator->user_id)
                ->update(['actv3Comision' => $dictaminator->actv3Comision]);
        }

        $this->info('Datos de comision de la actividad 3.1, transferidos exitosamente.');
    }
}