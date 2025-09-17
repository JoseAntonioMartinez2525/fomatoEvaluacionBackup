<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluatorSignature extends Model
{
    use HasFactory;

    // Definir los campos que se pueden llenar masivamente
    protected $fillable = [
        //'dictaminador_id',
        'user_id',
        'email',
        'evaluator_name',
        'evaluator_name_2',
        'evaluator_name_3',
        'signature_path',
        'signature_path_2',
        'signature_path_3',
        'user_type',
    ];


    protected $table = 'evaluador_por_firmas';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

public function hasAvailableSignatureSlot()
{
    return $this->signature_path_1 === null || $this->signature_path_2 === null || $this->signature_path_3 === null;
}

    public function hasAvailableEvaluatorName()
    {
        return $this->evaluator_name === null || $this->evaluator_name_2 === null || $this->evaluator_name_3 === null;
    }

    public function addSignaturePath($path)
    {
        if (empty($this->signature_path)) {
            $this->signature_path = $path;
        } elseif (empty($this->signature_path_2)) {
            $this->signature_path_2 = $path;
        } elseif (empty($this->signature_path_3)) {
            $this->signature_path_3 = $path;
        }
        //$this->save();
    }

    public function addEvaluatorName($name)
    {
        if (empty($this->evaluator_name)) {
            $this->evaluator_name = $name;
        } elseif (empty($this->evaluator_name_2)) {
            $this->evaluator_name_2 = $name;
        } elseif (empty($this->evaluator_name_3)) {
            $this->evaluator_name_3 = $name;
        }
        //$this->save();
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'evaluador_por_firmas';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }
}

