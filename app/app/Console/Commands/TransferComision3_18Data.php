<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3_18Data extends Command
{
    protected $signature = 'transfer:comision3_18';
    protected $description = 'Transferir datos de comision3_18 de dictaminators_response_form3_18 a users_response_form3_18';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_18')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_18')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision3_18' => $dictaminator->comision3_18]);
        }

        $this->info('Datos de comision de la actividad 3_18, transferidos exitosamente.');
    }
}