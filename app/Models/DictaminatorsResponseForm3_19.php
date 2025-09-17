<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DictaminatorsResponseForm3_19 extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'dictaminador_id',
        'user_id',
        'email',
        'score3_19',
        'comision3_19',
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
        'comCGUtitular',
        'comCGUespecial',
        'comCGUpermanente',
        'comCAACtitular',
        'comCAACintegCom',
        'comComDepart',
        'comComPEDPD',
        'comComPartPos',
        'comRespPos',
        'comRespCarrera',
        'comRespProd',
        'comRespLab',
        'comExamProf',
        'comExamAcademicos',
        'comPRODEPformResp',
        'comPRODEPformInteg',
        'comPRODEPenconsResp',
        'comPRODEPenconsInteg',
        'comPRODEPconsResp',
        'comPRODEPconsInteg',
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

    public $incrementing = true; // Indicates the primary key is not auto-incrementing
    protected $primaryKey = 'id'; // Specifies the primary key
    //protected $keyType = 'bigint'; // Specifies the key type


    protected $table = 'dictaminators_response_form3_19';
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
        return $this->morphedByMany(
            UsersResponseForm3_19::class,            // Modelo objetivo
            'id_type',                  // Nombre del campo morph
            'dictaminador_docente',               // Nombre de la tabla pivot
            'dictaminador_form_id',               // Llave de la tabla de dictaminadores
            'user_id'                             // Llave de la tabla objetivo en la relación polimórfica
        )
            ->withPivot('form_type', 'docente_email') // Campos adicionales en la tabla pivot
            ->withTimestamps();
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'dictaminators_response_form3_19';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }


}


