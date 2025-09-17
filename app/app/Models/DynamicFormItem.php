<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicFormItem extends Model
{
    use HasFactory;

    protected $fillable = ['dynamic_form_id', 'key', 'value'];

    public function form()
    {
        return $this->belongsTo(DynamicForm::class, 'dynamic_form_id');
    }
}
