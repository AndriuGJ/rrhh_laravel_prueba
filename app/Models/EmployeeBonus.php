<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeBonus extends Model
{
    protected $table = 'employee_bonuses';

    protected $fillable = [
        'employee_id',
        'bonus_type_id',
        'monto',
        'estado',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'estado' => 'boolean',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function bonusType(): BelongsTo
    {
        return $this->belongsTo(BonusType::class, 'bonus_type_id');
    }
}