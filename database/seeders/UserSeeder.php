<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador Sistema',
            'email' => 'rrhh@gmail.com',
            'password' => Hash::make('admin123'),
            'rol_id' => 1,
            'estado' => true,
        ]);
    }
}
