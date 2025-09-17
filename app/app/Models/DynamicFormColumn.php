<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicFormColumn extends Model
{
    protected $table = 'dynamic_form_columns'; // Specify the table name if different

    protected $fillable = [
        'dynamic_form_id',
        'column_name',
        'created_at',
        'updated_at',
    ];

    public function dynamicForm()
    {
        return $this->belongsTo(DynamicForm::class, 'dynamic_form_id');
    }
}
