<?php
namespace App\Listeners;

use App\Events\EvaluationCompleted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransferComisionDataListener
{
    // Mapeo de modelos de origen a su respectivo campo de comisión y modelo de destino
    protected $modelMappings = [
        \App\Models\DictaminatorsResponseForm2::class => [
            'sourceField' => 'comision1',
            'destinationModel' => \App\Models\UsersResponseForm2::class,
            'destinationField' => 'comision1',
        ],
        \App\Models\DictaminatorsResponseForm2_2::class => [
            'sourceField' => 'actv2Comision',
            'destinationModel' => \App\Models\UsersResponseForm2_2::class,
            'destinationField' => 'actv2Comision',
        ],
        \App\Models\DictaminatorsResponseForm3_1::class => [
            'sourceField' => 'actv3Comision',
            'destinationModel' => \App\Models\UsersResponseForm3_1::class,
            'destinationField' => 'actv3Comision',
        ],
        \App\Models\DictaminatorsResponseForm3_2::class => [
            'sourceField' => 'comision3_2',
            'destinationModel' => \App\Models\UsersResponseForm3_2::class,
            'destinationField' => 'comision3_2',
        ],

        \App\Models\DictaminatorsResponseForm3_3::class => [
            'sourceField' => 'comision3_3',
            'destinationModel' => \App\Models\UsersResponseForm3_3::class,
            'destinationField' => 'comision3_3',
        ],
        \App\Models\DictaminatorsResponseForm3_4::class => [
            'sourceField' => 'comision3_4',
            'destinationModel' => \App\Models\UsersResponseForm3_4::class,
            'destinationField' => 'comision3_4',
        ],
        \App\Models\DictaminatorsResponseForm3_5::class => [
            'sourceField' => 'comision3_5',
            'destinationModel' => \App\Models\UsersResponseForm3_5::class,
            'destinationField' => 'comision3_5',
        ],
        \App\Models\DictaminatorsResponseForm3_6::class => [
            'sourceField' => 'comision3_6',
            'destinationModel' => \App\Models\UsersResponseForm3_6::class,
            'destinationField' => 'comision3_6',
        ],
        \App\Models\DictaminatorsResponseForm3_7::class => [
            'sourceField' => 'comision3_7',
            'destinationModel' => \App\Models\UsersResponseForm3_7::class,
            'destinationField' => 'comision3_7',
        ],
        \App\Models\DictaminatorsResponseForm3_8::class => [
            'sourceField' => 'comision3_8',
            'destinationModel' => \App\Models\UsersResponseForm3_8::class,
            'destinationField' => 'comision3_8',
        ],
        \App\Models\DictaminatorsResponseForm3_8_1::class => [
            'sourceField' => 'comision3_8_1',
            'destinationModel' => \App\Models\UsersResponseForm3_8_1::class,
            'destinationField' => 'comision3_8_1',
        ],
        \App\Models\DictaminatorsResponseForm3_9::class => [
            'sourceField' => 'comision3_9',
            'destinationModel' => \App\Models\UsersResponseForm3_9::class,
            'destinationField' => 'comision3_9',
        ],
        \App\Models\DictaminatorsResponseForm3_10::class => [
            'sourceField' => 'comision3_10',
            'destinationModel' => \App\Models\UsersResponseForm3_10::class,
            'destinationField' => 'comision3_10',
        ],
        \App\Models\DictaminatorsResponseForm3_11::class => [
            'sourceField' => 'comision3_11',
            'destinationModel' => \App\Models\UsersResponseForm3_11::class,
            'destinationField' => 'comision3_11',
        ],
        \App\Models\DictaminatorsResponseForm3_12::class => [
            'sourceField' => 'comision3_12',
            'destinationModel' => \App\Models\UsersResponseForm3_12::class,
            'destinationField' => 'comision3_12',
        ],
        \App\Models\DictaminatorsResponseForm3_13::class => [
            'sourceField' => 'comision3_13',
            'destinationModel' => \App\Models\UsersResponseForm3_13::class,
            'destinationField' => 'comision3_13',
        ],
        \App\Models\DictaminatorsResponseForm3_14::class => [
            'sourceField' => 'comision3_14',
            'destinationModel' => \App\Models\UsersResponseForm3_14::class,
            'destinationField' => 'comision3_14',
        ],
        \App\Models\DictaminatorsResponseForm3_15::class => [
            'sourceField' => 'comision3_15',
            'destinationModel' => \App\Models\UsersResponseForm3_15::class,
            'destinationField' => 'comision3_15',
        ],
        \App\Models\DictaminatorsResponseForm3_16::class => [
            'sourceField' => 'comision3_16',
            'destinationModel' => \App\Models\UsersResponseForm3_16::class,
            'destinationField' => 'comision3_16',
        ],
        \App\Models\DictaminatorsResponseForm3_17::class => [
            'sourceField' => 'comision3_17',
            'destinationModel' => \App\Models\UsersResponseForm3_17::class,
            'destinationField' => 'comision3_17',
        ],
        \App\Models\DictaminatorsResponseForm3_18::class => [
            'sourceField' => 'comision3_18',
            'destinationModel' => \App\Models\UsersResponseForm3_18::class,
            'destinationField' => 'comision3_18',
        ],             
        \App\Models\DictaminatorsResponseForm3_19::class => [
            'sourceField' => 'comision3_19',
            'destinationModel' => \App\Models\UsersResponseForm3_19::class,
            'destinationField' => 'comision3_19',
        ],
    ];

    public function handle(EvaluationCompleted $event)
    {
        // Log::info('Evento EvaluationCompleted recibido para user_id: ' . $event->user_id);

        // // Include dynamic model mappings
        // $this->modelMappings = array_merge($this->modelMappings, $this->getDynamicModelMappings());

        // foreach ($this->modelMappings as $sourceModel => $mapping) {
        //     $sourceModelInstance = new $sourceModel;
        //     $tableName = $sourceModelInstance->getTable();
        //     Log::info('Verificando en la tabla ' . $tableName . ' para user_id: ' . $event->user_id);

        //     $dictaminator = DB::table($tableName)
        //         ->where('user_id', $event->user_id)
        //         ->first();

        //     if ($dictaminator) {
        //         $destinationModel = $mapping['destinationModel'];
        //         $destinationModelInstance = new $destinationModel;
        //         $destinationTableName = $destinationModelInstance->getTable();
        //         $sourceField = $mapping['sourceField'];
        //         $destinationField = $mapping['destinationField'];

        //         Log::info('Transfiriendo datos de ' . $tableName . ' a ' . $destinationTableName . ' para user_id: ' . $event->user_id);

        //         $updateResult = DB::table($destinationTableName)
        //             ->where('user_id', $dictaminator->user_id)
        //             ->update([$destinationField => $dictaminator->$sourceField]);

        //         if ($updateResult) {
        //             Log::info('Datos transferidos con éxito para user_id: ' . $event->user_id);
        //         } else {
        //             Log::warning('No se pudieron actualizar los datos en ' . $destinationTableName . ' para user_id: ' . $event->user_id);
        //         }
        //     } else {
        //         Log::warning('No se encontraron datos en ' . $tableName . ' para user_id: ' . $event->user_id);
        //     }
        // }
    // Consolidate all commission data into consolidated_responses

        Log::info('Evento EvaluationCompleted recibido para user_id cuando los formualrios ya existen: ' . $event->user_id);
    $userId = $event->user_id;

    // Aggregate all commission fields for this user
    $data = [
        'user_id' => $userId,
        'user_email' => DB::table('users')->where('id', $userId)->value('email'),
        'user_type' => 'docente', // or fetch from users table
        'comision1' => DB::table('dictaminators_response_form2')->where('user_id', $userId)->value('comision1') ?? 0,
        'actv2Comision' => DB::table('dictaminators_response_form2_2')->where('user_id', $userId)->value('actv2Comision') ?? 0,
        'actv3Comision' => DB::table('dictaminators_response_form3_1')->where('user_id', $userId)->value('actv3Comision') ?? 0,
        'comision3_2' => DB::table('dictaminators_response_form3_2')->where('user_id', $userId)->value('comision3_2') ?? 0,
        'comision3_3' => DB::table('dictaminators_response_form3_3')->where('user_id', $userId)->value('comision3_3') ?? 0,
        'comision3_4' => DB::table('dictaminators_response_form3_4')->where('user_id', $userId)->value('comision3_4') ?? 0,
        'comision3_5' => DB::table('dictaminators_response_form3_5')->where('user_id', $userId)->value('comision3_5') ?? 0,
        'comision3_6' => DB::table('dictaminators_response_form3_6')->where('user_id', $userId)->value('comision3_6') ?? 0,
        'comision3_7' => DB::table('dictaminators_response_form3_7')->where('user_id', $userId)->value('comision3_7') ?? 0,
        'comision3_8' => DB::table('dictaminators_response_form3_8')->where('user_id', $userId)->value('comision3_8') ?? 0,
        'comision3_8_1' => DB::table('dictaminators_response_form3_8_1')->where('user_id', $userId)->value('comision3_8_1') ?? 0,
        'comision3_9' => DB::table('dictaminators_response_form3_9')->where('user_id', $userId)->value('comision3_9') ?? 0,
        'comision3_10' => DB::table('dictaminators_response_form3_10')->where('user_id', $userId)->value('comision3_10') ?? 0,
        'comision3_11' => DB::table('dictaminators_response_form3_11')->where('user_id', $userId)->value('comision3_11') ?? 0,
        'comision3_12' => DB::table('dictaminators_response_form3_12')->where('user_id', $userId)->value('comision3_12') ?? 0,
        'comision3_13' => DB::table('dictaminators_response_form3_13')->where('user_id', $userId)->value('comision3_13') ?? 0,
        'comision3_14' => DB::table('dictaminators_response_form3_14')->where('user_id', $userId)->value('comision3_14') ?? 0,
        'comision3_15' => DB::table('dictaminators_response_form3_15')->where('user_id', $userId)->value('comision3_15') ?? 0,
        'comision3_16' => DB::table('dictaminators_response_form3_16')->where('user_id', $userId)->value('comision3_16') ?? 0,
        'comision3_17' => DB::table('dictaminators_response_form3_17')->where('user_id', $userId)->value('comision3_17') ?? 0,
        'comision3_18' => DB::table('dictaminators_response_form3_18')->where('user_id', $userId)->value('comision3_18') ?? 0,
        'comision3_19' => DB::table('dictaminators_response_form3_19')->where('user_id', $userId)->value('comision3_19') ?? 0,
        // ...etc...
    ];

    // Upsert into consolidated_responses
    DB::table('consolidated_responses')->upsert($data, ['user_id'], array_keys($data));

    }

    protected function getDynamicModelMappings()
    {
        $dynamicMappings = [];
        // Define the base model name
        $baseModel = 'App\Models\DictaminatorsResponseForm';

        // Check for models with a specific pattern
        for ($i = 1; $i <= 30; $i++) {
            // Check for base models without fixed mappings
            $modelName = $baseModel . $i;
            if (class_exists($modelName) && !in_array($modelName, $this->getFixedModels())) {
                $dynamicMappings[$modelName] = [
                    'sourceField' => 'comision' . $i,
                    'destinationModel' => 'App\Models\UsersResponseForm' . $i,
                    'destinationField' => 'comision' . $i,
                ];
            }

            // Check for models with additional suffixes
            for ($j = 1; $j <= 10; $j++) {
                $suffixModelName = $baseModel . $i . '_' . $j;
                if (class_exists($suffixModelName) && !in_array($suffixModelName, $this->getFixedModels())) {
                    $dynamicMappings[$suffixModelName] = [
                        'sourceField' => 'comision' . $i . '_' . $j,
                        'destinationModel' => 'App\Models\UsersResponseForm' . $i . '_' . $j,
                        'destinationField' => 'comision' . $i . '_' . $j,
                    ];
                }
            }
        }

        return $dynamicMappings;
    }

    protected function getFixedModels()
    {
        return [
            \App\Models\DictaminatorsResponseForm2::class,
            \App\Models\DictaminatorsResponseForm2_2::class,
            \App\Models\DictaminatorsResponseForm3_1::class,
            \App\Models\DictaminatorsResponseForm3_2::class,
            \App\Models\DictaminatorsResponseForm3_3::class,
            \App\Models\DictaminatorsResponseForm3_4::class,
            \App\Models\DictaminatorsResponseForm3_5::class,
            \App\Models\DictaminatorsResponseForm3_6::class,
            \App\Models\DictaminatorsResponseForm3_7::class,
            \App\Models\DictaminatorsResponseForm3_8::class,
            \App\Models\DictaminatorsResponseForm3_8_1::class,
            \App\Models\DictaminatorsResponseForm3_9::class,
            \App\Models\DictaminatorsResponseForm3_10::class,
            \App\Models\DictaminatorsResponseForm3_11::class,
            \App\Models\DictaminatorsResponseForm3_12::class,
            \App\Models\DictaminatorsResponseForm3_13::class,
            \App\Models\DictaminatorsResponseForm3_14::class,
            \App\Models\DictaminatorsResponseForm3_15::class,
            \App\Models\DictaminatorsResponseForm3_16::class,
            \App\Models\DictaminatorsResponseForm3_17::class,
            \App\Models\DictaminatorsResponseForm3_18::class,
            \App\Models\DictaminatorsResponseForm3_19::class,
        ];
    }

}

