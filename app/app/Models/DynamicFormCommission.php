<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicFormCommission extends Model
{
    protected $table = 'dynamic_form_commission';

    protected $fillable = [
        'dynamic_form_id',
        'dynamic_form_column_id',
        'dynamic_form_value_id',
        'user_id',
        'email_docente',
        'user_type',
        'row_identifier',
        'puntaje_input_values',
        'puntaje_comision',
        'observaciones',
    ];

    public function dynamicForm()
    {
        return $this->belongsTo(DynamicForm::class, 'dynamic_form_id');
    }

    public function column()
    {
        return $this->belongsTo(DynamicFormColumn::class, 'dynamic_form_column_id');
    }

    public function value()
    {
        return $this->belongsTo(DynamicFormValue::class, 'dynamic_form_value_id');
    }
}