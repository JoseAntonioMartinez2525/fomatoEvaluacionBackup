<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersResponseForm3_3 extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'email',
        'score3_3',
        'rc1',
        'rc2',
        'rc3',
        'rc4',
        'stotal1',
        'stotal2',
        'stotal3',
        'stotal4',
        'obs3_3_1',
        'obs3_3_2',
        'obs3_3_3',
        'obs3_3_4',
        'user_type',

    ];

    protected $table = 'users_response_form3_3';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'users_response_form3_3';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }


    public function dictaminadores()
    {
        return $this->belongsToMany(DictaminatorsResponseForm3_3::class, 'dictaminador_docente')
            ->withPivot('form_type')
            ->withTimestamps();
    }

}




