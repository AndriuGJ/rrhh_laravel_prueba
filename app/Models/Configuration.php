<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $table = 'configurations';

    protected $fillable = [
        'clave',
        'valor',
        'descripcion',
    ];

    public static function get($clave, $default = null)
    {
        $config = static::where('clave', $clave)->first();
        return $config ? $config->valor : $default;
    }

    public static function set($clave, $valor)
    {
        return static::updateOrCreate(
            ['clave' => $clave],
            ['valor' => $valor]
        );
    }
}