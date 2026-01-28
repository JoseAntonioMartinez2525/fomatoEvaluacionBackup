<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicForm extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'email',
        'user_type',
        'form_name',
        'form_type',
        'puntaje_maximo',
        'acreditacion',
        'filas',
        'columnas',
        'form_structure',
        'form_data',
    ];

    protected $casts = [
        'form_structure' => 'array', // Codifica/decodifica automáticamente a/desde JSON
        'form_data' => 'array',      // Codifica/decodifica automáticamente a/desde JSON
    ];
}
