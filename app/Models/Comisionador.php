<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comisionador extends Model
{
    use HasFactory;

    /**
     * Definir la tabla explícitamente para evitar error de pluralización (comisionadors).
     */
    protected $table = 'comisionadores';

    protected $fillable = [
        'user_id',
        'maestroId',
        'nombre',
        'primerApellido',
        'segundoApellido',
        'email',
        'departamento',
        'area',
        'firma_grafica',
        'fecha_convocatoria',
    ];

    protected $casts = [
        'fecha_convocatoria' => 'array', // Cast automático a JSON
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
