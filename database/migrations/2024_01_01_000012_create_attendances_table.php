<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('fecha');
            $table->time('hora_entrada')->nullable();
            $table->time('hora_salida')->nullable();
            $table->enum('estado', ['PUNTUAL', 'TARDE', 'FALTA', 'NO_APORTE'])->default('PUNTUAL');
            $table->integer('minutos_tardanza')->default(0);
            $table->enum('tipo_licencia', ['CG', 'SH', 'ME', 'PA', 'PN'])->nullable();
            $table->date('fecha_fin_licencia')->nullable();
            $table->text('observacion')->nullable();
            $table->enum('turno', ['MANANA', 'TARDE', 'COMPLETO'])->default('COMPLETO');
            $table->boolean('es_falta_turno')->default(false)->comment('1=Falta de un turno específico (50% del día)');
            $table->date('fecha_original')->nullable()->comment('Fecha original del excel');
            $table->boolean('estado_registro')->default(true);
            $table->timestamps();

            $table->index(['employee_id', 'fecha']);
            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
