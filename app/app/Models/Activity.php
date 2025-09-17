<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    // Especificar el nombre de la tabla (opcional si el nombre sigue la convención plural)
    protected $table = 'activities';

    // Permitir asignación masiva para estos campos
    protected $fillable = [
        'activity_id', 
        'score', 
        'commission_score', 
        'observation',
    ];

    // Deshabilitar timestamps si no los necesitas
    public $timestamps = true;
}
