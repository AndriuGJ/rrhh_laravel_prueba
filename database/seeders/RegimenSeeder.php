<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegimenSeeder extends Seeder
{
    public function run(): void
    {
        $regimenes = [
            ['DL 276', 'Decreto Legislativo 276 (DL 276)'],
            ['DL 728', 'Decreto Legislativo 728 (DL 728)'],
            ['CAS', 'Contrato Administrativo de Servicios (CAS)'],
            ['LEY SERVIR', 'Ley del Servicio Civil (Ley Servir - Ley 30057)'],
            ['DT REGIDORES', 'Dieta Regidores'],
            ['CAS JUDICIAL', 'Cas Mandato Judicial'],
            ['CAS CONFIANZA', 'Cas Confianza'],
        ];

        foreach ($regimenes as [$nombre, $descripcion]) {
            if (! DB::table('regimens')->where('nombre', $nombre)->exists()) {
                DB::table('regimens')->insert([
                    'nombre' => $nombre,
                    'descripcion' => $descripcion,
                    'estado' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
