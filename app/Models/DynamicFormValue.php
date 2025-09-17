<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicFormValue extends Model
{
    protected $table = 'dynamic_form_values'; // Specify the table name if different

    protected $fillable = [
        'dynamic_form_id',
        'dynamic_form_column_id',
        'row_index',
        'value',
        'puntaje_maximo',
        'created_at',
        'updated_at',
    ];

    public function dynamicForm()
    {
        return $this->belongsTo(DynamicForm::class, 'dynamic_form_id');
    }

    public function column()
    {
        return $this->belongsTo(DynamicFormColumn::class, 'dynamic_form_column_id');
    }
}
