<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferComision1Data extends Command
{
    protected $signature = 'transfer:comision1';
    protected $description = 'Transferir datos de comision1 de dictaminators_response_form2 a users_response_form2';

    public function handle()
    {
        $dictaminators = DB::table('dictaminators_response_form2')->get();

        foreach ($dictaminators as $dictaminator) {
            DB::table('users_response_form2')
                ->where('user_id', $dictaminator->user_id)
                ->update(['comision1' => $dictaminator->comision1]);
        }

        $this->info('Datos de comision1 transferidos exitosamente.');
    }
}
