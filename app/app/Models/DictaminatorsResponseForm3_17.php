<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DictaminatorsResponseForm3_17 extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'dictaminador_id',
        'user_id',
        'email',
        'score3_17',
        'comision3_17',
        'cantDifusionExt',
        'cantDifusionInt',
        'cantRepDifusionExt',
        'cantRepDifusionInt',
        'subtotalDifusionExt',
        'subtotalDifusionInt',
        'subtotalRepDifusionExt',
        'subtotalRepDifusionInt',
        'comisionDifusionExt',
        'comisionDifusionInt',
        'comisionRepDifusionExt',
        'comisionRepDifusionInt',
        'obsDifusionExt',
        'obsDifusionInt',
        'obsRepDifusionExt',
        'obsRepDifusionInt',        
        'user_type',

    ];

    public $incrementing = true; // Indicates the primary key is not auto-incrementing
    protected $primaryKey = 'id'; // Specifies the primary key
    //protected $keyType = 'bigint'; // Specifies the key type


    protected $table = 'dictaminators_response_form3_17';
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function usersResponseForm1()
    {
        return $this->belongsTo(UsersResponseForm1::class, 'user_id', 'user_id');
    }

    public function docentes()
    {
        return $this->morphedByMany(
            UsersResponseForm3_17::class,            // Modelo objetivo
            'id_type',                    // Nombre del campo morph
            'dictaminador_docente',               // Nombre de la tabla pivot
            'dictaminador_form_id',               // Llave de la tabla de dictaminadores
            'user_id'                             // Llave de la tabla objetivo en la relación polimórfica
        )
            ->withPivot('form_type', 'docente_email') // Campos adicionales en la tabla pivot
            ->withTimestamps();
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'dictaminators_response_form3_17';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }


}


