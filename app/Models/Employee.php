<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'nombres',
        'apellidos',
        'dni',
        'qr_code',
        'email',
        'telefono',
        'fecha_nacimiento',
        'direccion',
        'cargo',
        'nivel',
        'sueldo_basico',
        'fecha_ingreso',
        'fecha_cese',
        'regimen_laboral',
        'regimen_pensionario',
        'cuspp',
        'banco',
        'cuenta_bancaria',
        'numero_cts',
        'meta',
        'rubro',
        'tiene_asignacion_familiar',
        'asignacion_familiar_porcentaje',
        'asignacion_familiar_monto',
        'bonificacion',
        'documento_ingreso',
        'documento_path',
        'conformidad_tregistro',
        'activo',
        'estado',
        'afp_especifica',
        'afp_tipo_comision',
        'afp_tiene_jubilacion',
        'aplica_renta_quinta',
        'renta_quinta_porcentaje',
        'renta_quinta_uit',
    ];

    protected $casts = [
        'tiene_asignacion_familiar' => 'boolean',
        'asignacion_familiar_porcentaje' => 'decimal:2',
        'asignacion_familiar_monto' => 'decimal:2',
        'sueldo_basico' => 'decimal:2',
        'bonificacion' => 'decimal:2',
        'conformidad_tregistro' => 'boolean',
        'activo' => 'boolean',
        'estado' => 'boolean',
        'afp_tiene_jubilacion' => 'boolean',
        'aplica_renta_quinta' => 'boolean',
        'renta_quinta_porcentaje' => 'decimal:2',
        'renta_quinta_uit' => 'decimal:2',
        'fecha_nacimiento' => 'date',
        'fecha_ingreso' => 'date',
        'fecha_cese' => 'date',
    ];

    public function employeeBonuses(): HasMany
    {
        return $this->hasMany(EmployeeBonus::class, 'employee_id');
    }

    public function dependents(): HasMany
    {
        return $this->hasMany(Dependent::class, 'employee_id');
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class, 'employee_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }

    public function getFullNameAttribute(): string
    {
        return $this->nombres.' '.$this->apellidos;
    }
}
