<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'nombre' => 'Administrador',
                'descripcion' => 'Acceso total al sistema',
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Usuario',
                'descripcion' => 'Acceso limitado al sistema',
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}