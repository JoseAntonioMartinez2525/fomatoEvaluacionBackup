<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DictaminatorsResponseForm2_2 extends RulesForm2_2
{
    use HasFactory;
    protected $fillable = [
        'id',
        'dictaminador_id',
        'user_id',
        'email',
        'hours',
        'horasPosgrado',
        'horasSemestre',
        'dse',
        'dse2',
        'comisionPosgrado',
        'comisionLic',
        'actv2Comision',
        'obs2',
        'obs2_2',
        'user_type',

    ];

    public $incrementing = true; 
    protected $primaryKey = 'id'; // Specifies the primary key
    //protected $keyType = 'bigint'; // Specifies the key type
    protected $table = 'dictaminators_response_form2_2';
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function usersResponseForm1()
    {
        return $this->belongsTo(UsersResponseForm1::class, 'user_id', 'user_id');
    }

    //users_response_form2_2
    public function docentes()
    {
        return $this->belongsToMany(UsersResponseForm2_2::class, 'dictaminador_docente')
            ->withPivot('form_type')
            ->withTimestamps();
    }
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'dictaminators_response_form2_2';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }


}





