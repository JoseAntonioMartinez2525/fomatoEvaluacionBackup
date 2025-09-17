<?php
/**
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


    

    public function evaluatorSignatures()
    {
        return $this->hasMany(EvaluatorSignature::class, 'user_id', 'id');
    }

    public function userResume()
    {
        return $this->hasOne(UserResume::class, 'user_id', 'id');
    }
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
}
