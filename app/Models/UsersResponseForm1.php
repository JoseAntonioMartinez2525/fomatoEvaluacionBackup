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
            $start = \Carbon\Carbon::parse($dates->start_date);
            $end = \Carbon\Carbon::parse($dates->end_date);
            
            // Formato: AñoInicio-AñoFin (ej. 2025-2026) o solo Año si es el mismo.
            if ($start->year !== $end->year) {
                return $start->year . '-' . $end->year;
            }
            
            // Si es el mismo año, calcular el número de convocatoria (I, II, etc.)
            // Contamos cuántos periodos de 'docentes_llenado' existen en este año hasta el actual (inclusive)
            $count = DB::table('evaluation_dates')
                ->where('type', 'docentes_llenado')
                ->whereYear('start_date', $start->year)
                ->where('id', '<=', $dates->id) // Usamos ID para mantener el orden cronológico de creación
                ->count();
            
            // Asegurar que el conteo sea al menos 1 para que toRoman devuelva 'I' como mínimo
            $count = $count > 0 ? $count : 1;

            return $start->year . '-' . self::toRoman($count);
        }
        
        return null;
    }

    private static function toRoman($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
}
