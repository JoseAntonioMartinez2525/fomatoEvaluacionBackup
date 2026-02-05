<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicFormResponse extends Model
{
    protected $fillable = [
        'dynamic_form_id',
        'user_id',
        'user_type',
        'data',
        'evaluador_id',
        'evaluador_email'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function form()
    {
        return $this->belongsTo(DynamicForm::class, 'dynamic_form_id');
    }
}
