<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Docente extends Model
{
    use HasFactory;

    protected $table = 'docentes';

    protected $fillable = [
        'nombre',
        'apellido_1',
        'apellido_2',
        'email',
        'departamento',
        'area',
        'fecha_convocatoria',
        'periodo',
    ];

    protected $casts = [
        'fecha_convocatoria' => 'array',
    ];

    protected static function booted()
    {
        // Antes de guardar, asignar fecha_convocatoria si no existe
        static::saving(function ($docente) {
            if (empty($docente->fecha_convocatoria)) {
                $evaluationDate = DB::table('evaluation_dates')
                    ->where('type', 'docentes_llenado')
                    ->orderBy('id', 'desc')
                    ->first();

                if ($evaluationDate) {
                    $docente->fecha_convocatoria = [
                        'start_date' => $evaluationDate->start_date,
                        'end_date' => $evaluationDate->end_date,
                    ];
                }
            }
        });

        // Después de guardar, sincronizar con la tabla users
        static::saved(function ($docente) {
            $user = User::where('email', $docente->email)->first();
            $fullName = trim("{$docente->nombre} {$docente->apellido_1} {$docente->apellido_2}");

            if ($user) {
                // Actualizar usuario existente
                $user->update([
                    'name' => $fullName,
                    'departamento' => $docente->departamento,
                ]);
            } else {
                // Crear nuevo usuario
                User::create([
                    'name' => $fullName,
                    'email' => $docente->email,
                    'user_type' => 'docente',
                    'departamento' => $docente->departamento,
                    'password' => Hash::make('password'), // Contraseña por defecto
                ]);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
