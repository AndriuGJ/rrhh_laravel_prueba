<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regimen extends Model
{
    protected $table = 'regimens';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];
}
