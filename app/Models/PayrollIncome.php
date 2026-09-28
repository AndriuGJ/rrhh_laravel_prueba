<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollIncome extends Model
{
    protected $table = 'payroll_incomes';

    protected $fillable = [
        'payroll_id',
        'concepto',
        'tipo',
        'monto',
        'estado',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'estado' => 'boolean',
    ];

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }
}