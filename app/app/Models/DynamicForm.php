<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_name',
        'puntaje_maximo',
        'user_id',
        'email',
        'user_type',
        'table_data',
        'acreditacion',
        'filas', 
        'columnas', 
    ];

    protected $casts = [
        'table_data' => 'array',  // Convierte la columna JSON en un array al obtenerla
    ];

    public function fields()
    {
        return $this->hasMany(DynamicFormField::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(DynamicFormItem::class, 'dynamic_form_id');
    }

    public function columns()
    {
        return $this->hasMany(DynamicFormColumn::class, 'dynamic_form_id');
    }

    public function values()
    {
        return $this->hasMany(DynamicFormValue::class, 'dynamic_form_id');
    }
}

