<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigurationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('configurations')->insert([
            [
                'clave' => 'horario_entrada',
                'valor' => '08:00',
                'descripcion' => 'Hora estándar de entrada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'clave' => 'horario_salida',
                'valor' => '17:00',
                'descripcion' => 'Hora estándar de salida',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'clave' => 'horario_entrada_manana',
                'valor' => '08:00',
                'descripcion' => 'Hora de entrada turno mañana',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'clave' => 'horario_salida_manana',
                'valor' => '13:00',
                'descripcion' => 'Hora de salida turno mañana',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'clave' => 'horario_entrada_tarde',
                'valor' => '14:00',
                'descripcion' => 'Hora de entrada turno tarde',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'clave' => 'horario_salida_tarde',
                'valor' => '17:00',
                'descripcion' => 'Hora de salida turno tarde',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'clave' => 'tolerancia_manana',
                'valor' => '5',
                'descripcion' => 'Minutos de tolerancia turno mañana',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'clave' => 'tolerancia_tarde',
                'valor' => '0',
                'descripcion' => 'Minutos de tolerancia turno tarde',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'clave' => 'essalud_tasa',
                'valor' => '9',
                'descripcion' => 'Tasa de ESSALUD (%) - Aporte del empleador',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'clave' => 'sctr_salud_tasa',
                'valor' => '0.8',
                'descripcion' => 'Tasa SCTR Salud (%) - Aporte del empleador',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'clave' => 'sctr_pensiones_tasa',
                'valor' => '0.8',
                'descripcion' => 'Tasa SCTR Pensiones (%) - Aporte del empleador',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'clave' => 'rmv',
                'valor' => '1025',
                'descripcion' => 'Remuneración Mínima Vital (S/)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        if (!DB::table('configurations')->where('clave', 'tipo_navegacion')->exists()) {
            DB::table('configurations')->insert([
                'clave' => 'tipo_navegacion',
                'valor' => 'header',
                'descripcion' => 'Tipo de navegación: header o sidebar',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $empresaDefaults = [
            ['empresa_nombre', 'Municipalidad Distrital de Pilcomayo', 'Nombre de la empresa'],
            ['empresa_nombre_corto', 'Municipalidad Pilcomayo', 'Nombre corto de la empresa'],
            ['empresa_ruc', '20199800061', 'RUC de la empresa'],
            ['empresa_direccion', 'Jr. Francisco Pizango N° 101 - Pilcomayo - Huaura - Lima', 'Dirección de la empresa'],
            ['empresa_telefono', '(064) 123-456', 'Teléfono de contacto'],
            ['empresa_email', 'rrhh@munipilcomayo.gob.pe', 'Correo de contacto'],
            ['empresa_logo', '', 'Ruta del logo personalizado (vacío = logo por defecto)'],
            ['empresa_fondo_login', '', 'Ruta del fondo del login (vacío = fondo por defecto)'],
        ];

        foreach ($empresaDefaults as [$clave, $valor, $descripcion]) {
            if (! DB::table('configurations')->where('clave', $clave)->exists()) {
                DB::table('configurations')->insert([
                    'clave' => $clave,
                    'valor' => $valor,
                    'descripcion' => $descripcion,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}