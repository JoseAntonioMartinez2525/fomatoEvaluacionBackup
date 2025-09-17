<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersResponseForm3_2 extends Model
{
     use HasFactory;
    protected $fillable = [
        'user_id',
        'email',
        'score3_2',
        'r1',
        'r2',
        'r3',
        'cant1',
        'cant2',
        'cant3',
        'obs3_2_1',
        'obs3_2_2',
        'obs3_2_3',
        'user_type',

    ];


    protected $table = 'users_response_form3_2';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'users_response_form3_2';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }

    public function dictaminadores()
    {
        return $this->belongsToMany(DictaminatorsResponseForm3_2::class, 'dictaminador_docente')
            ->withPivot('form_type')
            ->withTimestamps();
    }

}


