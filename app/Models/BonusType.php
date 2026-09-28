<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BonusType extends Model
{
    protected $table = 'bonus_types';

    protected $fillable = [
        'nombre',
        'descripcion',
        'es_remunerativo',
        'estado',
    ];

    protected $casts = [
        'es_remunerativo' => 'boolean',
        'estado' => 'boolean',
    ];

    public function employeeBonuses(): HasMany
    {
        return $this->hasMany(EmployeeBonus::class, 'bonus_type_id');
    }
}