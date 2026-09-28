<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    protected $table = 'payrolls';

    protected $fillable = [
        'employee_id',
        'periodo',
        'sueldo_basico',
        'asignacion_familiar',
        'horas_extras_horas',
        'monto_horas_extras',
        'faltas',
        'monto_faltas',
        'tardanzas',
        'monto_tardanzas',
        'remuneracion_bruta',
        'essalud',
        'sctr_salud',
        'sctr_pensiones',
        'total_aportes_empleador',
        'conceptos_no_remunerativos',
        'bonificaciones_remunerativas',
        'bonificaciones_no_remunerativas',
        'sistema_pensiones',
        'retencion_pensiones',
        'renta_quinta',
        'total_ingresos',
        'total_descuentos',
        'neto_pagar',
        'estado',
        'fecha_generacion',
        'fecha_pago',
        'estado_registro',
    ];

    protected $casts = [
        'sueldo_basico' => 'decimal:2',
        'asignacion_familiar' => 'decimal:2',
        'horas_extras_horas' => 'decimal:2',
        'monto_horas_extras' => 'decimal:2',
        'faltas' => 'integer',
        'monto_faltas' => 'decimal:2',
        'tardanzas' => 'integer',
        'monto_tardanzas' => 'decimal:2',
        'remuneracion_bruta' => 'decimal:2',
        'essalud' => 'decimal:2',
        'sctr_salud' => 'decimal:2',
        'sctr_pensiones' => 'decimal:2',
        'total_aportes_empleador' => 'decimal:2',
        'conceptos_no_remunerativos' => 'decimal:2',
        'bonificaciones_no_remunerativas' => 'decimal:2',
        'retencion_pensiones' => 'decimal:2',
        'renta_quinta' => 'decimal:2',
        'total_ingresos' => 'decimal:2',
        'total_descuentos' => 'decimal:2',
        'neto_pagar' => 'decimal:2',
        'estado_registro' => 'boolean',
        'fecha_generacion' => 'datetime',
        'fecha_pago' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function incomes(): HasMany
    {
        return $this->hasMany(PayrollIncome::class, 'payroll_id');
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(PayrollDiscount::class, 'payroll_id');
    }
}
