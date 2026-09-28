<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $table = 'attendances';

    protected $fillable = [
        'employee_id',
        'fecha',
        'hora_entrada',
        'hora_salida',
        'estado',
        'minutos_tardanza',
        'tipo_licencia',
        'fecha_fin_licencia',
        'observacion',
        'turno',
        'es_falta_turno',
        'fecha_original',
        'estado_registro',
        'origen',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_entrada' => 'datetime:H:i',
        'hora_salida' => 'datetime:H:i',
        'minutos_tardanza' => 'integer',
        'es_falta_turno' => 'boolean',
        'estado_registro' => 'boolean',
        'fecha_fin_licencia' => 'date',
        'fecha_original' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getDescuentoAttribute(): float
    {
        $employee = $this->employee()->first();
        if (! $employee) {
            return 0;
        }

        $sueldo = (float) $employee->sueldo_basico;

        if ($this->estado === 'TARDE' && $this->minutos_tardanza > 0) {
            $minutoValue = $sueldo / 14400;

            return round($minutoValue * $this->minutos_tardanza, 2);
        }

        if ($this->estado === 'FALTA') {
            if ($this->es_falta_turno) {
                return round($sueldo / 60, 2);
            }

            return round($sueldo / 30, 2);
        }

        return 0;
    }
}
