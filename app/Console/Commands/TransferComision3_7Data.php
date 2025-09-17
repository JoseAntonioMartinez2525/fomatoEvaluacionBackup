<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision3_7Data extends Command
{
    protected $signature = 'transfer:comision3_7';
    protected $description = 'Transferir datos de comision3_7 de dictaminators_response_form3_7 a users_response_form3_7';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form3_7')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form3_7')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision3_7' => $dictaminator->comision3_7]);
        }

        $this->info('Datos de comision de la actividad 3_7, transferidos exitosamente.');
    }
}