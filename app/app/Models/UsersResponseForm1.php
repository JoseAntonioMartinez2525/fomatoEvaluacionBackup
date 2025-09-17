<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class UsersResponseForm1 extends BaseResponse
{
    protected $fillable = [
        'user_id',
        'convocatoria',
        'periodo',
        'nombre',
        'area',
        'departamento',
    ];

    protected $table = 'users_responses_form1';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function __call($method, $parameters)
    {
        if (preg_match('/^dictaminatorsResponseForm(\d+(_\d+)?)$/', $method, $matches)) {
            $formNumber = $matches[1];
            $modelClass = 'App\\Models\\DictaminatorsResponseForm' . $formNumber;

            if (class_exists($modelClass)) {
                return $this->hasMany($modelClass, 'user_id', 'user_id');
            }
        }

        return parent::__call($method, $parameters);
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'users_responses_form1';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }
}




