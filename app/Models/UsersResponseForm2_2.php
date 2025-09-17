<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersResponseForm2_2 extends RulesForm2_2
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'email',
        'user_type',
        'hours',
        'horasPosgrado',
        'horasSemestre',
        'dse',
        'dse2',
        'obs2',
        'obs2_2',

    ];

    protected $table = 'users_response_form2_2';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'users_response_form2_2';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }


    public function dictaminadores()
    {
        return $this->belongsToMany(DictaminatorsResponseForm2_2::class, 'dictaminador_docente')
            ->withPivot('form_type')
            ->withTimestamps();
    }


}





