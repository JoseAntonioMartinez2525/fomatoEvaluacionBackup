<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersResponseForm3_19 extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'email',
        'score3_19',
        'cantCGUtitular',   
        'subtotalCGUtitular',
        'cantCGUespecial',
        'subtotalCGUespecial',
        'cantCGUpermanente',
        'subtotalCGUpermanente',
        'cantCAACtitular',
        'subtotalCAACtitular',
        'cantCAACintegCom',
        'subtotalCAACintegCom',
        'cantComDepart',
        'subtotalComDepart',
        'cantComPEDPD',
        'subtotalComPEDPD',
        'cantComPartPos',
        'subtotalComPartPos',
        'cantRespPos',
        'subtotalRespPos',
        'cantRespCarrera',
        'subtotalRespCarrera',
        'cantRespProd',
        'subtotalRespProd',
        'cantRespLab',
        'subtotalRespLab',
        'cantExamProf',
        'subtotalExamProf',
        'cantExamAcademicos',
        'subtotalExamAcademicos',
        'cantPRODEPformResp',
        'subtotalPRODEPformResp',
        'cantPRODEPformInteg',
        'subtotalPRODEPformInteg',
        'cantPRODEPenconsResp',
        'subtotalPRODEPenconsResp',
        'cantPRODEPenconsInteg',
        'subtotalPRODEPenconsInteg',
        'cantPRODEPconsResp',
        'subtotalPRODEPconsResp',
        'cantPRODEPconsInteg',
        'subtotalPRODEPconsInteg',
        //'comision3_19',
        'obsCGUtitular',
        'obsCGUespecial',
        'obsCGUpermanente',
        'obsCAACtitular',
        'obsCAACintegCom',
        'obsComDepart',
        'obsComPEDPD',
        'obsComPartPos',
        'obsRespPos',
        'obsRespCarrera',
        'obsRespProd',
        'obsRespLab',
        'obsExamProf',
        'obsExamAcademicos',
        'obsPRODEPformResp',
        'obsPRODEPformInteg',
        'obsPRODEPenconsResp',
        'obsPRODEPenconsInteg',
        'obsPRODEPconsResp',
        'obsPRODEPconsInteg',
        'user_type',


    ];
    protected $table = 'users_response_form3_19';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'users_response_form3_19';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }

    public function dictaminadores()
    {
        return $this->morphedByMany(
            DictaminatorsResponseForm3_19::class,    // Modelo objetivo
            'id_type',                    // Nombre del campo morph
            'dictaminador_docente',               // Nombre de la tabla pivot
            'user_id',                            // Llave de la tabla de usuarios
            'dictaminador_form_id'                // Llave de la tabla objetivo en la relación polimórfica
        )
            ->withPivot('form_type', 'docente_email') // Campos adicionales en la tabla pivot
            ->withTimestamps();
    }
}
