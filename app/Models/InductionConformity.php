<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InductionConformity extends Model
{
    protected $table = 'induction_conformities';

    protected $fillable = [
        'employee_id',
        'tipo',
        'responsable',
        'fecha',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'fecha' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}