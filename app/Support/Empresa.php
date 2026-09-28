<?php

namespace App\Support;

use App\Models\Configuration;
use Illuminate\Support\Facades\Storage;

class Empresa
{
    protected static ?array $datos = null;

    protected const DEFAULTS = [
        'empresa_nombre' => 'Municipalidad Distrital de Pilcomayo',
        'empresa_nombre_corto' => 'Municipalidad Pilcomayo',
        'empresa_ruc' => '20199800061',
        'empresa_direccion' => 'Jr. Francisco Pizango N° 101 - Pilcomayo - Huaura - Lima',
        'empresa_telefono' => '(064) 123-456',
        'empresa_email' => 'rrhh@munipilcomayo.gob.pe',
        'empresa_logo' => '',
        'empresa_fondo_login' => '',
    ];

    public static function datos(): array
    {
        if (static::$datos === null) {
            $filas = Configuration::whereIn('clave', array_keys(static::DEFAULTS))
                ->pluck('valor', 'clave')
                ->all();

            static::$datos = array_merge(static::DEFAULTS, $filas);
        }

        return static::$datos;
    }

    public static function get(string $clave): string
    {
        $datos = static::datos();

        return (string) ($datos[$clave] ?? (static::DEFAULTS[$clave] ?? ''));
    }

    public static function nombre(): string
    {
        return static::get('empresa_nombre');
    }

    public static function nombreCorto(): string
    {
        return static::get('empresa_nombre_corto');
    }

    public static function ruc(): string
    {
        return static::get('empresa_ruc');
    }

    public static function direccion(): string
    {
        return static::get('empresa_direccion');
    }

    public static function telefono(): string
    {
        return static::get('empresa_telefono');
    }

    public static function email(): string
    {
        return static::get('empresa_email');
    }

    public static function logoPath(): string
    {
        $logo = static::get('empresa_logo');

        if ($logo !== '' && Storage::disk('public')->exists($logo)) {
            return storage_path('app/public/'.$logo);
        }

        return public_path('img/logo.jpg');
    }

    public static function logoUrl(): string
    {
        $logo = static::get('empresa_logo');

        if ($logo !== '' && Storage::disk('public')->exists($logo)) {
            return Storage::disk('public')->url($logo);
        }

        return asset('img/logo.jpg');
    }

    public static function fondoUrl(): string
    {
        $fondo = static::get('empresa_fondo_login');

        if ($fondo !== '' && Storage::disk('public')->exists($fondo)) {
            return Storage::disk('public')->url($fondo);
        }

        return asset('img/fondo_pilcomayo.jpg');
    }

    public static function flush(): void
    {
        static::$datos = null;
    }
}
