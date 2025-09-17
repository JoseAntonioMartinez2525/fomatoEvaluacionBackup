<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserResume extends Model
{
    
    use HasFactory;
    protected $fillable = [
        'dictaminador_id',
        'user_id',
        'email',
        'comision_actividad_1_total',
        'comision_actividad_2_total',
        'comision_actividad_3_total',
        'total_puntaje',
        'minima_calidad',
        'minima_total',
        'user_type',


    ];
    protected $table = 'users_final_resume';
    public function user()
    {
        return $this->belongsTo(User::class, 'dictaminador_id', 'id');
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'users_final_resume';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }
}
