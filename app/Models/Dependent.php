<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dependent extends Model
{
    protected $table = 'dependents';

    protected $fillable = [
        'employee_id',
        'nombres',
        'apellidos',
        'dni',
        'tipo_vinculo',
        'fecha_nacimiento',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'fecha_nacimiento' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getFullNameAttribute(): string
    {
        return $this->nombres . ' ' . $this->apellidos;
    }
}