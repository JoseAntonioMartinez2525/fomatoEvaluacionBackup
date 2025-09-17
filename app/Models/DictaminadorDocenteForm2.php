<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DictaminadorDocenteForm2 extends Model
{
    use HasFactory;
    protected $table = 'dictaminador_docente_form2';

    protected $fillable = [
        'dictaminador_id',
        'user_id',
        'dictaminador_email',
        'docente_email',
        'horasActv2',
        'puntajeEvaluar',
        'comision1',
        'obs1'
    ];

    // Definir las relaciones con los modelos User
    public function dictaminador()
    {
        return $this->belongsTo(User::class, 'dictaminador_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
