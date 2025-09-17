<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DictaminatorsResponseForm2 extends RulesForm2
{
    use HasFactory;
    protected $fillable = [
        'id',
        'dictaminador_id',
        'user_id',
        'email',
        'horasActv2',
        'puntajeEvaluar',
        'comision1',
        'obs1',
        'user_type',
        'form_type',

    ];

    public $incrementing = true;  // Indicates the primary key is not auto-incrementing
    protected $primaryKey = 'id';  // Specifies the primary key
    

    protected $table = 'dictaminators_response_form2';
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
        return $this->belongsToMany(UsersResponseForm2::class, 'dictaminador_docente')
            ->withPivot('form_type')
            ->withTimestamps();
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'dictaminators_response_form2';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }


}




