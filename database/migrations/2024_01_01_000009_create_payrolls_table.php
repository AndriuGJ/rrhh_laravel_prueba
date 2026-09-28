<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('periodo', 7);
            $table->decimal('sueldo_basico', 10, 2)->default(0);
            $table->decimal('asignacion_familiar', 10, 2)->default(0);
            $table->decimal('horas_extras_horas', 10, 2)->default(0);
            $table->decimal('monto_horas_extras', 10, 2)->default(0);
            $table->integer('faltas')->default(0);
            $table->decimal('monto_faltas', 10, 2)->default(0);
            $table->integer('tardanzas')->default(0);
            $table->decimal('monto_tardanzas', 10, 2)->default(0);
            $table->decimal('remuneracion_bruta', 10, 2)->default(0);
            $table->decimal('essalud', 10, 2)->default(0)->comment('Aporte ESSALUD 9% del empleador');
            $table->decimal('sctr_salud', 10, 2)->default(0)->comment('SCTR Salud del empleador');
            $table->decimal('sctr_pensiones', 10, 2)->default(0)->comment('SCTR Pensiones del empleador');
            $table->decimal('total_aportes_empleador', 10, 2)->default(0)->comment('Total ESSALUD + SCTR');
            $table->decimal('conceptos_no_remunerativos', 10, 2)->default(0);
            $table->decimal('bonificaciones_no_remunerativas', 10, 2)->default(0);
            $table->decimal('bonificaciones_remunerativas', 10, 2)->default(0);
            $table->decimal('renta_quinta', 10, 2)->default(0);
            $table->enum('sistema_pensiones', ['AFP', 'ONP', 'NO_APORTE'])->default('AFP');
            $table->decimal('retencion_pensiones', 10, 2)->default(0);
            $table->decimal('total_ingresos', 10, 2)->default(0);
            $table->decimal('total_descuentos', 10, 2)->default(0);
            $table->decimal('neto_pagar', 10, 2)->default(0);
            $table->enum('estado', ['GENERADA', 'PAGADA', 'ANULADA'])->default('GENERADA');
            $table->timestamp('fecha_generacion')->useCurrent();
            $table->date('fecha_pago')->nullable();
            $table->boolean('estado_registro')->default(true);
            $table->timestamps();

            $table->index('periodo');
            $table->index('employee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
