<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3_8Data extends Command
{
    protected $signature = 'transfer:comision3_8';
    protected $description = 'Transferir datos de comision3_8 de dictaminators_response_form3_8 a users_response_form3_8';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_8')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_8')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision3_8' => $dictaminator->comision3_8]);
        }

        $this->info('Datos de comision de la actividad 3_8, transferidos exitosamente.');
    }
}