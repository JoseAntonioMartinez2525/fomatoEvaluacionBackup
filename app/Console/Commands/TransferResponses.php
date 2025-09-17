<?php

namespace App\Console\Commands;

use App\Models\DictaminatorsResponseForm3_10;
use App\Models\DictaminatorsResponseForm3_11;
use App\Models\DictaminatorsResponseForm3_12;
use App\Models\DictaminatorsResponseForm3_13;
use App\Models\DictaminatorsResponseForm3_14;
use App\Models\DictaminatorsResponseForm3_15;
use App\Models\DictaminatorsResponseForm3_16;
use App\Models\DictaminatorsResponseForm3_17;
use App\Models\DictaminatorsResponseForm3_18;
use App\Models\DictaminatorsResponseForm3_19;
use App\Models\DictaminatorsResponseForm2;
use App\Models\DictaminatorsResponseForm2_2;
use App\Models\DictaminatorsResponseForm3_1;
use App\Models\DictaminatorsResponseForm3_2;
use App\Models\DictaminatorsResponseForm3_3;
use App\Models\DictaminatorsResponseForm3_4;
use App\Models\DictaminatorsResponseForm3_5;
use App\Models\DictaminatorsResponseForm3_6;
use App\Models\DictaminatorsResponseForm3_7;
use App\Models\DictaminatorsResponseForm3_8;
use App\Models\DictaminatorsResponseForm3_8_1;
use App\Models\DictaminatorsResponseForm3_9;


use App\Models\UsersResponseForm1;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferResponses extends Command
{
    protected $signature = 'transfer:responses';
    protected $description = 'Transfer responses data to the consolidated table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Transfer dictaminador responses
        $this->transferDictaminadorResponses();

        $this->info('Data transfer and consolidation completed.');
    }

    private function transferDictaminadorResponses()
    {
        $models = [
            UsersResponseForm1::class,
            DictaminatorsResponseForm2::class,
            DictaminatorsResponseForm2_2::class,
            DictaminatorsResponseForm3_1::class,
            DictaminatorsResponseForm3_2::class,
            DictaminatorsResponseForm3_3::class,
            DictaminatorsResponseForm3_4::class,
            DictaminatorsResponseForm3_5::class,
            DictaminatorsResponseForm3_6::class,
            DictaminatorsResponseForm3_7::class,
            DictaminatorsResponseForm3_8::class,
            DictaminatorsResponseForm3_8_1::class,
            DictaminatorsResponseForm3_9::class,
            DictaminatorsResponseForm3_10::class,
            DictaminatorsResponseForm3_11::class,
            DictaminatorsResponseForm3_12::class,
            DictaminatorsResponseForm3_13::class,
            DictaminatorsResponseForm3_14::class,
            DictaminatorsResponseForm3_15::class,
            DictaminatorsResponseForm3_16::class,
            DictaminatorsResponseForm3_17::class,
            DictaminatorsResponseForm3_18::class,
            DictaminatorsResponseForm3_19::class,
        ];

        $consolidatedData = [];
        

        foreach ($models as $model) {
            $responses = $model::with('user')->get();
            foreach ($responses as $response) {
                if (!isset($consolidatedData[$response->user_id])) {
                    $consolidatedData[$response->user_id] = [
                        'user_id' => $response->user_id,

                        'user_email' => $response->user ? $response->user->email : 'N/A',
                        'user_type' => 'docente',
                        'comision1' => 0,
                        'actv2Comision' => 0,
                        'actv3Comision' => 0,
                        // Inicializa otros campos de comisión
                        'comision3_2' => 0,
                        'comision3_3' => 0,
                        'comision3_4' => 0,
                        'comision3_5' => 0,
                        'comision3_6' => 0,
                        'comision3_7' => 0,
                        'comision3_8' => 0,
                        'comision3_8_1' => 0,
                        'comision3_9' => 0,
                        'comision3_10' => 0,
                        'comision3_11' => 0,
                        'comision3_12' => 0,
                        'comision3_13' => 0,
                        'comision3_14' => 0,
                        'comision3_15' => 0,
                        'comision3_16' => 0,
                        'comision3_17' => 0,
                        'comision3_18' => 0,
                        'comision3_19' => 0,
                    ];
                }


                // Acumula los valores de las comisiones
                $consolidatedData[$response->user_id]['comision1'] += $response->comision1 ?? 0;
                $consolidatedData[$response->user_id]['actv2Comision'] += $response->actv2Comision ?? 0;
                $consolidatedData[$response->user_id]['actv3Comision'] += $response->actv3Comision ?? 0;
                $consolidatedData[$response->user_id]['comision3_2'] += $response->comision3_2 ?? 0;
                $consolidatedData[$response->user_id]['comision3_3'] += $response->comision3_3 ?? 0;
                $consolidatedData[$response->user_id]['comision3_4'] += $response->comision3_4 ?? 0;
                $consolidatedData[$response->user_id]['comision3_5'] += $response->comision3_5 ?? 0;
                $consolidatedData[$response->user_id]['comision3_6'] += $response->comision3_6 ?? 0;
                $consolidatedData[$response->user_id]['comision3_7'] += $response->comision3_7 ?? 0;
                $consolidatedData[$response->user_id]['comision3_8'] += $response->comision3_8 ?? 0;
                $consolidatedData[$response->user_id]['comision3_8_1'] += $response->comision3_8_1 ?? 0;
                $consolidatedData[$response->user_id]['comision3_9'] += $response->comision3_9 ?? 0;
                $consolidatedData[$response->user_id]['comision3_10'] += $response->comision3_10 ?? 0;
                $consolidatedData[$response->user_id]['comision3_11'] += $response->comision3_11 ?? 0;
                $consolidatedData[$response->user_id]['comision3_12'] += $response->comision3_12 ?? 0;
                $consolidatedData[$response->user_id]['comision3_13'] += $response->comision3_13 ?? 0;
                $consolidatedData[$response->user_id]['comision3_14'] += $response->comision3_14 ?? 0;
                $consolidatedData[$response->user_id]['comision3_15'] += $response->comision3_15 ?? 0;
                $consolidatedData[$response->user_id]['comision3_16'] += $response->comision3_16 ?? 0;
                $consolidatedData[$response->user_id]['comision3_17'] += $response->comision3_17 ?? 0;
                $consolidatedData[$response->user_id]['comision3_18'] += $response->comision3_18 ?? 0;
                $consolidatedData[$response->user_id]['comision3_19'] += $response->comision3_19 ?? 0;
            }
        }

        foreach ($consolidatedData as $data) {
            DB::table('consolidated_responses')->upsert($data, ['user_id'], [
                'comision1',
                'actv2Comision',
                'actv3Comision',
                'comision3_2',
                'comision3_3',
                'comision3_4',
                'comision3_5',
                'comision3_6',
                'comision3_7',
                'comision3_8',
                'comision3_8_1',
                'comision3_9',
                'comision3_10',
                'comision3_11',
                'comision3_12',
                'comision3_13',
                'comision3_14',
                'comision3_15',
                'comision3_16',
                'comision3_17',
                'comision3_18',
                'comision3_19',
            ]);
        }
    }
}
