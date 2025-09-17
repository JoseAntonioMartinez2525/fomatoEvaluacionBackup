<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsersResponseForm2 extends RulesForm2
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'email',
        'user_type',
        'horasActv2',
        'puntajeEvaluar',
        'obs1',
        'form_type',

    ];

    protected $table = 'users_response_form2';


    public function dictaminadores()
    {
        return $this->belongsToMany(DictaminatorsResponseForm2::class, 'dictaminador_docente')
            ->withPivot('form_type')
            ->withTimestamps();
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'users_response_form2';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }


}




