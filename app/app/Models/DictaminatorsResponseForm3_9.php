<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DictaminatorsResponseForm3_9 extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'dictaminador_id',
        'user_id',
        'email',
        'score3_9',
        'comision3_9',
        'puntaje3_9_1',
        'puntaje3_9_2',
        'puntaje3_9_3',
        'puntaje3_9_4',
        'puntaje3_9_5',
        'puntaje3_9_6',
        'puntaje3_9_7',
        'puntaje3_9_8',
        'puntaje3_9_9',
        'puntaje3_9_10',
        'puntaje3_9_11',
        'puntaje3_9_12',
        'puntaje3_9_13',
        'puntaje3_9_14',
        'puntaje3_9_15',
        'puntaje3_9_16',
        'puntaje3_9_17',
        'tutorias1',
        'tutorias2',
        'tutorias3',
        'tutorias4',
        'tutorias5',
        'tutorias6',
        'tutorias7',
        'tutorias8',
        'tutorias9',
        'tutorias10',
        'tutorias11',
        'tutorias12',
        'tutorias13',
        'tutorias14',
        'tutorias15',
        'tutorias16',
        'tutorias17',
        'tutoriasComision1',
        'tutoriasComision2',
        'tutoriasComision3',
        'tutoriasComision4',
        'tutoriasComision5',
        'tutoriasComision6',
        'tutoriasComision7',
        'tutoriasComision8',
        'tutoriasComision9',
        'tutoriasComision10',
        'tutoriasComision11',
        'tutoriasComision12',
        'tutoriasComision13',
        'tutoriasComision14',
        'tutoriasComision15',
        'tutoriasComision16',
        'tutoriasComision17',
        'obs3_9_1',
        'obs3_9_2',
        'obs3_9_3',
        'obs3_9_4',
        'obs3_9_5',
        'obs3_9_6',
        'obs3_9_7',
        'obs3_9_8',
        'obs3_9_9',
        'obs3_9_10',
        'obs3_9_11',
        'obs3_9_12',
        'obs3_9_13',
        'obs3_9_14',
        'obs3_9_15',
        'obs3_9_16',
        'obs3_9_17',
        'user_type',

    ];

    public $incrementing = true; // Indicates the primary key is not auto-incrementing
    protected $primaryKey = 'id'; // Specifies the primary key
    //protected $keyType = 'bigint'; // Specifies the key type


    protected $table = 'dictaminators_response_form3_9';


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
        return $this->belongsToMany(UsersResponseForm3_9::class, 'dictaminador_docente')
            ->withPivot('form_type')
            ->withTimestamps();
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'dictaminators_response_form3_9';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }


}


