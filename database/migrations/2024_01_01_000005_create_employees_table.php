<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('dni', 8)->unique();
            $table->string('email', 100)->unique();
            $table->string('telefono', 20)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->text('direccion')->nullable();
            $table->string('cargo', 100)->nullable();
            $table->string('nivel', 100)->nullable();
            $table->decimal('sueldo_basico', 10, 2)->default(0);
            $table->date('fecha_ingreso')->nullable();
            $table->date('fecha_cese')->nullable();
            $table->string('regimen_laboral', 150)->nullable();
            $table->enum('regimen_pensionario', ['AFP', 'ONP', 'NO_APORTE'])->default('AFP');
            $table->string('cuspp', 20)->nullable();
            $table->string('banco', 50)->nullable();
            $table->string('cuenta_bancaria', 50)->nullable();
            $table->string('numero_cts', 50)->nullable();
            $table->string('meta', 50)->nullable();
            $table->string('rubro', 100)->nullable();
            $table->boolean('tiene_asignacion_familiar')->default(false);
            $table->decimal('asignacion_familiar_porcentaje', 5, 2)->default(0)->comment('Porcentaje de asignacion familiar');
            $table->decimal('asignacion_familiar_monto', 10, 2)->default(0)->comment('Monto base para calculo de asignacion familiar');
            $table->decimal('bonificacion', 10, 2)->default(0);
            $table->string('documento_ingreso', 100)->nullable();
            $table->boolean('conformidad_tregistro')->default(false);
            $table->boolean('activo')->default(true);
            $table->boolean('estado')->default(true);
            $table->enum('afp_especifica', ['HABITAT', 'INTEGRA', 'PRIMA', 'PROFUTURO'])->nullable();
            $table->enum('afp_tipo_comision', ['FLUJO', 'MIXTA'])->default('FLUJO');
            $table->boolean('afp_tiene_jubilacion')->default(false);
            $table->boolean('aplica_renta_quinta')->default(false);
            $table->decimal('renta_quinta_porcentaje', 5, 2)->default(8.00);
            $table->decimal('renta_quinta_uit', 10, 2)->default(38500);
            $table->timestamps();

            $table->index('dni');
            $table->index('email');
            $table->index(['estado', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
