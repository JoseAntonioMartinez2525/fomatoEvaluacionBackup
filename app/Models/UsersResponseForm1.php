<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsersResponseForm1 extends BaseResponse
{
    protected $fillable = [
        'user_id',
        'convocatoria',
        'periodo',
        'nombre',
        'area',
        'departamento',
    ];

    protected $table = 'users_responses_form1';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function __call($method, $parameters)
    {
        if (preg_match('/^dictaminatorsResponseForm(\d+(_\d+)?)$/', $method, $matches)) {
            $formNumber = $matches[1];
            $modelClass = 'App\\Models\\DictaminatorsResponseForm' . $formNumber;

            if (class_exists($modelClass)) {
                return $this->hasMany($modelClass, 'user_id', 'user_id');
            }
        }

        return parent::__call($method, $parameters);
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'users_responses_form1';
        $this->connection = 'mysql';
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc')->first();
    }

    /**
     * Boot del modelo para asignar periodo automáticamente al guardar.
     */
    protected static function booted()
    {
        static::saving(function ($model) {
            $periodo = static::calculateCurrentPeriod();
            if ($periodo) {
                $model->periodo = $periodo;
            }
        });
    }

    /**
     * Calcula el periodo actual basado en la tabla evaluation_dates.
     */
    public static function calculateCurrentPeriod()
    {
        $dates = DB::table('evaluation_dates')
            ->where('type', 'docentes_llenado')
            ->orderBy('id', 'desc')
            ->first();
        
        if ($dates && $dates->start_date && $dates->end_date) {
            $start = Carbon::parse($dates->start_date);
            $end = Carbon::parse($dates->end_date);
            
            // Formato: AñoInicio-AñoFin (ej. 2025-2026) o solo Año si es el mismo.
            if ($start->year === $end->year) {
                return (string)$start->year;
            }
            return $start->year . '-' . $end->year;
        }
        
        return null;
    }
}
