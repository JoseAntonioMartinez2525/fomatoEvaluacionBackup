<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\ResponseInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

abstract class RulesForm2 extends Model implements ResponseInterface
{
    // Implementa el método createResponse de la interfaz
    public static function createResponse(array $data)
    {
        // Validar y limpiar los datos según sea necesario
        $validatedData = self::validateData($data);

        // Crear y guardar el nuevo registro en la base de datos
        return self::create($validatedData);
    }


    /**
     * Método para validar y limpiar los datos de entrada
     *
     * @param array $data
     * @return array
     */
    private static function validateData(array $data)
    {

        //lógica de validación y limpieza de datos   
        $rules = [
            'user_id' => 'required|exists:users,id',
            'email' => 'required|exists:users,email',
            'horasActv2' => 'numeric',
            'puntajeEvaluar' => 'numeric|between:0,9999.99',
            'obs1' => 'string|nullable',
            

        ];
        // Perform validation
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            \Log::error('Validation failed:', $validator->errors()->all());
            throw new \Illuminate\Validation\ValidationException($validator);
        }
        // Logging the validated data
        \Log::info('Validated data:', $validator->validated());
        return $validator->validated();
    }
}