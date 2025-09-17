<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PuntajeMaximo3_8_1 extends Model
{
   use HasFactory;

    protected $table = 'puntajes_maximos';
    protected $fillable = ['clave', 'valor'];
}
