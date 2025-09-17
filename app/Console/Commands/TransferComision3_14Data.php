<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3_14Data extends Command
{
    protected $signature = 'transfer:comision3_14';
    protected $description = 'Transferir datos de comision3_14 de dictaminators_response_form3_14 a users_response_form3_14';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_14')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_14')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision3_14' => $dictaminator->comision3_14]);
        }

        $this->info('Datos de comision de la actividad 3_14, transferidos exitosamente.');
    }
}