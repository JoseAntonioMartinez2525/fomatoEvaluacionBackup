<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
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
class TransferController extends Controller
{
    protected function checkAndTransfer($modelClass)
    {
        if ($this->checkIfTablesAreFilled($modelClass)) {
            Artisan::call('transfer:responses');
        }
    }

    private function checkIfTablesAreFilled($modelClass)
    {
        switch ($modelClass) {
            case 'DictaminatorsResponseForm2':
                return DB::table('dictaminators_response_form2')->whereNotNull('comision1')->exists();
            case 'DictaminatorsResponseForm2_2':
                return DB::table('dictaminators_response_form2_2')->whereNotNull('actv2Comision')->exists();
            case 'DictaminatorsResponseForm3_1':
                return DB::table('dictaminators_response_form3_1')->whereNotNull('actv3Comision')->exists();
            case 'DictaminatorsResponseForm3_2':
                return DB::table('dictaminators_response_form3_2')->whereNotNull('comision3_2')->exists();
            case 'DictaminatorsResponseForm3_3':
                return DB::table('dictaminators_response_form3_3')->whereNotNull('comision3_3')->exists();
            case 'DictaminatorsResponseForm3_4':
                return DB::table('dictaminators_response_form3_4')->whereNotNull('comision3_4')->exists();
            case 'DictaminatorsResponseForm3_5':
                return DB::table('dictaminators_response_form3_5')->whereNotNull('comision3_5')->exists();
            case 'DictaminatorsResponseForm3_6':
                return DB::table('dictaminators_response_form3_6')->whereNotNull('comision3_6')->exists();
            case 'DictaminatorsResponseForm3_7':
                return DB::table('dictaminators_response_form3_7')->whereNotNull('comision3_7')->exists();
            case 'DictaminatorsResponseForm3_8':
                return DB::table('dictaminators_response_form3_8')->whereNotNull('comision3_8')->exists();
            case 'DictaminatorsResponseForm3_8_1':
                return DB::table('dictaminators_response_form3_8_1')->whereNotNull('comision3_8_1')->exists();                
            case 'DictaminatorsResponseForm3_9':
                return DB::table('dictaminators_response_form3_9')->whereNotNull('comision3_9')->exists();
            case 'DictaminatorsResponseForm3_10':
                return DB::table('dictaminators_response_form3_10')->whereNotNull('comision3_10')->exists();
            case 'DictaminatorsResponseForm3_11':
                return DB::table('dictaminators_response_form3_11')->whereNotNull('comision3_11')->exists();
            case 'DictaminatorsResponseForm3_12':
                return DB::table('dictaminators_response_form3_12')->whereNotNull('comision3_12')->exists();
            case 'DictaminatorsResponseForm3_13':
                return DB::table('dictaminators_response_form3_13')->whereNotNull('comision3_13')->exists();
            case 'DictaminatorsResponseForm3_14':
                return DB::table('dictaminators_response_form3_14')->whereNotNull('comision3_14')->exists();
            case 'DictaminatorsResponseForm3_15':
                return DB::table('dictaminators_response_form3_15')->whereNotNull('comision3_15')->exists();
            case 'DictaminatorsResponseForm3_16':
                return DB::table('dictaminators_response_form3_16')->whereNotNull('comision3_16')->exists();
            case 'DictaminatorsResponseForm3_17':
                return DB::table('dictaminators_response_form3_17')->whereNotNull('comision3_17')->exists();
            case 'DictaminatorsResponseForm3_18':
                return DB::table('dictaminators_response_form3_18')->whereNotNull('comision3_18')->exists();
            case 'DictaminatorsResponseForm3_19':
                return DB::table('dictaminators_response_form3_19')->whereNotNull('comision3_19')->exists();
            default:
                return false; // Si el modelo no coincide, retornar false
        }
    }

}
