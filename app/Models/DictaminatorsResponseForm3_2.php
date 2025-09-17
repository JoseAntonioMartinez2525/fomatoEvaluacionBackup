<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DictaminatorsResponseForm3_2 extends Model
{
    use HasFactory; protected $fillable = [
        'id',
        'dictaminador_id',
        'user_id',
        'email',
        'score3_2',
        'comision3_2',
        'r1',
        'r2',
        'r3',
        'cant1',
        'cant2',
        'cant3',
        'prom90_100',
        'prom80_90',
        'prom70_80',
        'obs3_2_1',
        'obs3_2_2',
        'obs3_2_3',
        'user_type',
    ];

    public $incrementing = true; // Indicates the primary key is not auto-incrementing
    protected $primaryKey = 'id'; // Specifies the primary key
    //protected $keyType = 'bigint'; // Specifies the key type


    protected $table = 'dictaminators_response_form3_2';
    
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
        return $this->belongsToMany(UsersResponseForm3_2::class, 'dictaminador_docente')
            ->withPivot('form_type')
            ->withTimestamps();
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'dictaminators_response_form3_2';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }


}


