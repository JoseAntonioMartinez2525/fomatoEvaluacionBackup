<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision2Data extends Command
{
    protected $signature = 'transfer:actv2Comision';
    protected $description = 'Transferir datos de comision2 de dictaminators_response_form2_2 a users_response_form2_2';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form2_2')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form2_2')
                ->where('user_id', $dictaminator->user_id)
                ->update(['actv2Comision' => $dictaminator->actv2Comision]);
        }

        $this->info('Datos de comision de la actividad 2, transferidos exitosamente.');
    }
}