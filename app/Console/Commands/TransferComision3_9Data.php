<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3_9Data extends Command
{
    protected $signature = 'transfer:comision3_9';
    protected $description = 'Transferir datos de comision3_9 de dictaminators_response_form3_9 a users_response_form3_9';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_9')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_9')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision3_9' => $dictaminator->comision3_9]);
        }

        $this->info('Datos de comision de la actividad 3_9, transferidos exitosamente.');
    }
}