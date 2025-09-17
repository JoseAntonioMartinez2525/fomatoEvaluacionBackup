<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicFormField extends Model
{
    use HasFactory;

    protected $fillable = ['form_id', 'field_name', 'field_type','field_options', 'is_required'];

    public function form()
    {
        return $this->belongsTo(DynamicForm::class, 'form_id');
    }

    
}
