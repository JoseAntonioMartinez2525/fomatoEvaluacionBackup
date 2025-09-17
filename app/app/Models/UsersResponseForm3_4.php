<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersResponseForm3_4 extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'email',
        'score3_4',
        'cantInternacional',
        'cantNacional',
        'cantidadRegional',
        'cantPreparacion',
        'cantInternacional2',
        'cantNacional2',
        'cantidadRegional2',
        'cantPreparacion2',
        'obs3_4_1',
        'obs3_4_2',
        'obs3_4_3',
        'obs3_4_4',
        'user_type',

    ];
    protected $table = 'users_response_form3_4';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'users_response_form3_4';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }

    public function dictaminadores()
    {
        return $this->morphedByMany(
            DictaminatorsResponseForm3_4::class,    // Modelo objetivo
            'id_type',                     // Nombre del campo morph
            'dictaminador_docente',               // Nombre de la tabla pivot
            'user_id',                            // Llave de la tabla de usuarios
            'dictaminador_form_id'                // Llave de la tabla objetivo en la relación polimórfica
        )
            ->withPivot('form_type', 'docente_email') // Campos adicionales en la tabla pivot
            ->withTimestamps();
    }

}

