<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DictaminatorsResponseForm3_15 extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'dictaminador_id',
        'user_id',
        'email',
        'score3_15',
        'comision3_15',
        'cantPatentes',
        'cantPrototipos',
        'subtotalPatentes',
        'subtotalPrototipos',
        'comisionPatententes',
        'comisionPrototipos',
        'obsPatentes',
        'obsPrototipos',
        'user_type',

    ];

    public $incrementing = true; // Indicates the primary key is not auto-incrementing
    protected $primaryKey = 'id'; // Specifies the primary key
    //protected $keyType = 'bigint'; // Specifies the key type


    protected $table = 'dictaminators_response_form3_15';
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
            UsersResponseForm3_15::class,            // Modelo objetivo
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
        $this->table = 'dictaminators_response_form3_15';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }


}


