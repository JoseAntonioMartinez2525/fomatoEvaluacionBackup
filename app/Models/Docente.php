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
        'primerApellido',
        'segundoApellido',
        'email',
        'departamento',
        'area',
        'maestroId',
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
            $fullName = trim("{$docente->nombre} {$docente->primerApellido} {$docente->segundoApellido}");

            if ($user) {
                // Verificar excepciones: Dictaminadores (config) y Controladores
                $dictaminadoresEmails = array_map('strtolower', array_keys(config('dictaminadores', [])));
                $isDictaminador = in_array(strtolower($docente->email), $dictaminadoresEmails);
                $isControlador = in_array($user->user_type, ['controlador', 'admin']);

                $updateData = [
                    'name' => $fullName,
                    'departamento' => $docente->departamento,
                ];

                // Si no es una excepción, forzar tipo docente para corregir posibles errores
                if (!$isDictaminador && !$isControlador) {
                    $updateData['user_type'] = 'docente';
                }

                // Actualizar usuario existente
                $user->update($updateData);
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
