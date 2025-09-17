<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersResponseForm3_9 extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'email',
        'score3_9',
        //'comision3_9',
        'puntaje3_9_1',
        'puntaje3_9_2',
        'puntaje3_9_3',
        'puntaje3_9_4',
        'puntaje3_9_5',
        'puntaje3_9_6',
        'puntaje3_9_7',
        'puntaje3_9_8',
        'puntaje3_9_9',
        'puntaje3_9_10',
        'puntaje3_9_11',
        'puntaje3_9_12',
        'puntaje3_9_13',
        'puntaje3_9_14',
        'puntaje3_9_15',
        'puntaje3_9_16',
        'puntaje3_9_17',
        'tutorias1',
        'tutorias2',
        'tutorias3',
        'tutorias4',
        'tutorias5',
        'tutorias6',
        'tutorias7',
        'tutorias8',
        'tutorias9',
        'tutorias10',
        'tutorias11',
        'tutorias12',
        'tutorias13',
        'tutorias14',
        'tutorias15',
        'tutorias16',
        'tutorias17',
        'obs3_9_1',
        'obs3_9_2',
        'obs3_9_3',
        'obs3_9_4',
        'obs3_9_5',
        'obs3_9_6',
        'obs3_9_7',
        'obs3_9_8',
        'obs3_9_9',
        'obs3_9_10',
        'obs3_9_11',
        'obs3_9_12',
        'obs3_9_13',
        'obs3_9_14',
        'obs3_9_15',
        'obs3_9_16',
        'obs3_9_17',
        'user_type',
    ];
    protected $table = 'users_response_form3_9';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'users_response_form3_9';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }

    public function dictaminadores()
    {
        return $this->morphedByMany(
            DictaminatorsResponseForm3_9::class,    // Modelo objetivo
            'id_type',                   // Nombre del campo morph
            'dictaminador_docente',               // Nombre de la tabla pivot
            'user_id',                            // Llave de la tabla de usuarios
            'dictaminador_form_id'                // Llave de la tabla objetivo en la relación polimórfica
        )
            ->withPivot('form_type', 'docente_email') // Campos adicionales en la tabla pivot
            ->withTimestamps();
    }
}
