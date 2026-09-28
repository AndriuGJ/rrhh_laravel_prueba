<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BonusTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('bonus_types')->insert([
            [
                'nombre' => 'CONVENIO COLECTIVO CENTRALIZADO 2022-2023',
                'descripcion' => 'Convenio colectivo centralizado 2022-2023',
                'es_remunerativo' => true,
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'INCREMENTO D.S 314.2023-EF',
                'descripcion' => 'Incremento D.S 314.2023-EF',
                'es_remunerativo' => true,
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'INCREMENTO D.S.280-2024-EF',
                'descripcion' => 'Incremento D.S.280-2024-EF',
                'es_remunerativo' => true,
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'INCREMENTO D.S.326-2025-EF',
                'descripcion' => 'Incremento D.S.326-2025-EF',
                'es_remunerativo' => true,
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'INCREMENTO EXP.JUD',
                'descripcion' => 'Incremento por Expediente Judicial',
                'es_remunerativo' => true,
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'BONO MOVILIDAD-REFRIGERIO EXP.JUD',
                'descripcion' => 'Bono movilidad-refrigerio por Expediente Judicial',
                'es_remunerativo' => false,
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'INCREMENTO D.S. 311-2022-EF',
                'descripcion' => 'Incremento por Decreto Supremo 311-2022-EF',
                'es_remunerativo' => true,
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'INCREMENTO D.S. 313-2023-EF',
                'descripcion' => 'Incremento por Decreto Supremo 313-2023-EF',
                'es_remunerativo' => true,
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'INCREMENTO D.S. 279-2024-EF',
                'descripcion' => 'Incremento por Decreto Supremo 279-2024-EF',
                'es_remunerativo' => true,
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'INCREMENTO D.S. 325-2025-EF',
                'descripcion' => 'Incremento por Decreto Supremo 325-2025-EF',
                'es_remunerativo' => true,
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'BONIFICACION ACTA DE CONCILIACION - MOVILIDAD',
                'descripcion' => 'Bonificacion acta de conciliacion - Movilidad',
                'es_remunerativo' => false,
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'BONIFICACION ACTA DE CONCILIACION - REFRIGERIO',
                'descripcion' => 'Bonificacion acta de conciliacion - Refrigerio',
                'es_remunerativo' => false,
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'REINTEGRO NO REMUNERABLE',
                'descripcion' => 'Reintegro no remunerable',
                'es_remunerativo' => false,
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'REINTEGRO REMUNERABLE',
                'descripcion' => 'Reintegro remunerable',
                'es_remunerativo' => true,
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}