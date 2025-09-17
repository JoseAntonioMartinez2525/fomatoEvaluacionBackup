<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DictaminatorsResponseForm3_1 extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'dictaminador_id',
        'user_id',
        'email',
        'elaboracion',
        'elaboracionSubTotal1',
        'comisionIncisoA',
        'elaboracion2',
        'elaboracionSubTotal2',
        'comisionIncisoB',
        'elaboracion3',
        'elaboracionSubTotal3',
        'comisionIncisoC',
        'elaboracion4',
        'elaboracionSubTotal4',
        'comisionIncisoD',
        'elaboracion5',
        'elaboracionSubTotal5',
        'comisionIncisoE',        
        'score3_1',
        'actv3Comision',
        'obs3_1_1',
        'obs3_1_2',
        'obs3_1_3',
        'obs3_1_4',
        'obs3_1_5',
        'user_type',
    ];

    public $incrementing = true; // Indicates the primary key is not auto-incrementing
    protected $primaryKey = 'id'; // Specifies the primary key
    //protected $keyType = 'bigint'; // Specifies the key type


    protected $table = 'dictaminators_response_form3_1';

    
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
        return $this->belongsToMany(UsersResponseForm3_1::class, 'dictaminador_docente')
            ->withPivot('form_type')
            ->withTimestamps();
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'dictaminators_response_form3_1';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }


}


