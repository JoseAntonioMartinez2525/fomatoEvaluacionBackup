<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalFormProvider extends Model
{
    use HasFactory;

    protected $table = 'external_form_providers';

    protected $fillable = [
        'name',
        'description',
        'token',
        'endpoint',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
