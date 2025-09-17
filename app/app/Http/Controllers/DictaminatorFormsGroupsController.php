<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserResume;
use App\Models\Users;
use Illuminate\Http\Request;

class DictaminatorFormsGroupsController extends Controller
{
 // Lista de todos los modelos de los formularios
    private $formModels = [
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
        UserResume::class,
    ];

    public function getDictaminadorData(Request $request)
    {
        $dictaminador_id = $request->query('dictaminador_id');
        $email = $request->query('email');

        $dictaminador = User::where('email', $email)
                            ->where('id', $dictaminador_id)
                            ->where('user_type', 'dictaminador')
                            ->first();

        if (!$dictaminador) {
            return response()->json(['error' => 'Dictaminador not found'], 404);
        }

        // Recopilar los datos de cada formulario
        $formData = [];
        foreach ($this->formModels as $model) {
            $formData[class_basename($model)] = $model::where('dictaminador_id', $dictaminador_id)->first();
        }

        return response()->json([
            'dictaminador' => [
                'dictaminador_id' => $dictaminador->id,
                'email' => $dictaminador->email,
            ],
            'form_data' => $formData,
        ]);
    }
}

