<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3_13Data extends Command
{
    protected $signature = 'transfer:comision3_13';
    protected $description = 'Transferir datos de comision3_13 de dictaminators_response_form3_13 a users_response_form3_13';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_13')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_13')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision3_13' => $dictaminator->comision3_13]);
        }

        $this->info('Datos de comision de la actividad 3_13, transferidos exitosamente.');
    }
}