<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DictaminadorDocenteF2 extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'dictaminador_id',
        'dictaminador_email',
        'docente_id',
        'docente_email',
        'horasActv2',
        'puntajeEvaluar',
        'comision1',
        'obs1',
        'user_type',

    ];

    public $incrementing = true; // Indicates the primary key is not auto-incrementing
    protected $primaryKey = 'dictaminador_id'; // Specifies the primary key
    protected $keyType = 'bigint'; // Specifies the key type

    protected $table = 'dictaminators_response_form2';
    public function user()
    {
        return $this->belongsTo(User::class, 'dictaminador_id', 'id');
    }

    public function usersResponseForm1()
    {
        return $this->belongsTo(UsersResponseForm1::class, 'user_id', 'user_id');
    }

    public function docentes()
    {
        return $this->belongsToMany(UsersResponseForm2::class, 'dictaminador_docente_form2');
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'dictaminador_docente_form2';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }
}
