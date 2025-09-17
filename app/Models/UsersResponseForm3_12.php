<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersResponseForm3_12 extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'email',
        'score3_12',
        //'comision3_12',
        'cantCientifico',
        'subtotalCientificos',
        'cantDivulgacion',
        'subtotalDivulgacion',
        'cantTraduccion',
        'subtotalTraduccion',
        'cantArbitrajeInt',
        'subtotalArbitrajeInt',
        'cantArbitrajeNac',
        'subtotalArbitrajeNac',
        'cantSinInt',
        'subtotalSinInt',
        'cantSinNac',
        'subtotalSinNac',
        'cantAutor',
        'subtotalAutor',
        'cantEditor',
        'subtotalEditor',
        'cantWeb',
        'subtotalWeb',
        'obsCientificos',
        'obsDivulgacion',
        'obsTraduccion',
        'obsArbitrajeInt',
        'obsArbitrajeNac',
        'obsSinInt',
        'obsSinNac',
        'obsAutor',
        'obsEditor',
        'obsWeb',
        'user_type',

    ];
    protected $table = 'users_response_form3_12';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'users_response_form3_12';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }

    public function dictaminadores()
    {
        return $this->morphedByMany(
            DictaminatorsResponseForm3_12::class,    // Modelo objetivo
            'id_type',                     // Nombre del campo morph
            'dictaminador_docente',               // Nombre de la tabla pivot
            'user_id',                            // Llave de la tabla de usuarios
            'dictaminador_form_id'                // Llave de la tabla objetivo en la relación polimórfica
        )
            ->withPivot('form_type', 'docente_email') // Campos adicionales en la tabla pivot
            ->withTimestamps();
    }
}
