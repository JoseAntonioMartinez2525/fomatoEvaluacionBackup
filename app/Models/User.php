<?php
/*
 Nombre del programador: José Antonio Martínez del Toro
Objetivo: Modelo de la tabla users, de los usuarios autentificados
Fecha de creación: 2024-06-03
 */
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'user_type',
        'email',
        'password',
        'is_dictaminador',
        'departamento',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Método mágico para manejar las relaciones de manera dinámica.
     * 
     * Esto permite generar automáticamente las relaciones con los formularios de dictaminador.
     */


    

    public function evaluatorSignatures() //cada dictaminador puede evaluar a muchos docentes
    {
        return $this->hasMany(EvaluatorSignature::class, 'user_id', 'id');
    }

    public function userResume() //extraer los datos de 3.Calidad en la docencia para el resumen del docente 
    {
        return $this->hasOne(UserResume::class, 'user_id', 'id');
    }

/**
 * Maneja dinámicamente las relaciones con modelos de formularios de dictaminador.
 *
 * @param string $method
 * @param array $parameters
 * @return mixed
 */

    public function __call($method, $parameters)
    {
        if (preg_match('/^dictaminators_response_form3_(\d+)$/', $method, $matches)) {
            $formNumber = $matches[1];
            $modelClass = 'App\\Models\\DictaminatorsResponseForm3_' . $formNumber;

            if (class_exists($modelClass)) {
                return $this->hasOne($modelClass, 'user_id', 'id');
            }
        }

        return parent::__call($method, $parameters);
    }

    public function dictaminadorSignature() //cada dictaminador ppuede tener solo una firma almacenada
    {
        return $this->hasOne(DictaminadorSignature::class, 'user_id', 'id');
    }


    // Relación con los dictaminadores que lo evaluaron
    public function dictaminadores()
    {
        return $this->belongsToMany(
            User::class,                // The User model (for dictaminadores)
            'dictaminador_docente',     // Tabla pivote
            'docente_id',               // FK del docente en la tabla pivote
            'dictaminador_id'           // FK of the related model (dictaminador) on the pivot table
        )->withTimestamps();
    }

public function tieneAlgunFormularioEvaluado() //verificar que formularios fueron los evaluados
{
    $formularios = [
        'form1'      => 'App\\Models\\UsersResponseForm1',
        'form2'      => 'App\\Models\\UsersResponseForm2',
        'form2_2'    => 'App\\Models\\UsersResponseForm2_2',
        'form3_1'    => 'App\\Models\\DictaminatorsResponseForm3_1',
        'form3_2'    => 'App\\Models\\DictaminatorsResponseForm3_2',
        'form3_3'    => 'App\\Models\\DictaminatorsResponseForm3_3',
        'form3_4'    => 'App\\Models\\DictaminatorsResponseForm3_4',
        'form3_5'    => 'App\\Models\\DictaminatorsResponseForm3_5',
        'form3_6'    => 'App\\Models\\DictaminatorsResponseForm3_6',
        'form3_7'    => 'App\\Models\\DictaminatorsResponseForm3_7',
        'form3_8'    => 'App\\Models\\DictaminatorsResponseForm3_8',
        'form3_8_1'  => 'App\\Models\\DictaminatorsResponseForm3_8_1',
        'form3_9'    => 'App\\Models\\DictaminatorsResponseForm3_9',
        'form3_10'   => 'App\\Models\\DictaminatorsResponseForm3_10',
        'form3_11'   => 'App\\Models\\DictaminatorsResponseForm3_11',
        'form3_12'   => 'App\\Models\\DictaminatorsResponseForm3_12',
        'form3_13'   => 'App\\Models\\DictaminatorsResponseForm3_13',
        'form3_14'   => 'App\\Models\\DictaminatorsResponseForm3_14',
        'form3_15'   => 'App\\Models\\DictaminatorsResponseForm3_15',
        'form3_16'   => 'App\\Models\\DictaminatorsResponseForm3_16',
        'form3_17'   => 'App\\Models\\DictaminatorsResponseForm3_17',
        'form3_18'   => 'App\\Models\\DictaminatorsResponseForm3_18',
        'form3_19'   => 'App\\Models\\DictaminatorsResponseForm3_19',
    ];

    foreach ($formularios as $form => $modelClass) {
        if (class_exists($modelClass) && $modelClass::where('user_id', $this->id)->exists()) {
            return true;
        }
    }

    return false;
}

    public function docente()
    {
        return $this->hasOne(Docente::class, 'email', 'email');
    }

}
