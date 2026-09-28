<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('employees')->where('regimen_laboral', 'Decreto Legislativo 276 (DL 276)')->update(['regimen_laboral' => 'DL 276']);
        DB::table('employees')->where('regimen_laboral', 'Decreto Legislativo 728 (DL 728)')->update(['regimen_laboral' => 'DL 728']);
        DB::table('employees')->where('regimen_laboral', 'Contrato Administrativo de Servicios (CAS)')->update(['regimen_laboral' => 'CAS']);
        DB::table('employees')->where('regimen_laboral', 'Ley del Servicio Civil (Ley Servir - Ley 30057)')->update(['regimen_laboral' => 'LEY SERVIR']);
    }

    public function down(): void
    {
        DB::table('employees')->where('regimen_laboral', 'DL 276')->update(['regimen_laboral' => 'Decreto Legislativo 276 (DL 276)']);
        DB::table('employees')->where('regimen_laboral', 'DL 728')->update(['regimen_laboral' => 'Decreto Legislativo 728 (DL 728)']);
        DB::table('employees')->where('regimen_laboral', 'CAS')->update(['regimen_laboral' => 'Contrato Administrativo de Servicios (CAS)']);
        DB::table('employees')->where('regimen_laboral', 'LEY SERVIR')->update(['regimen_laboral' => 'Ley del Servicio Civil (Ley Servir - Ley 30057)']);
    }
};
