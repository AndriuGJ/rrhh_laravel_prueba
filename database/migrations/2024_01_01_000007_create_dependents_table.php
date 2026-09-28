<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dependents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('dni', 8);
            $table->enum('tipo_vinculo', ['H', 'C', 'P', 'M', 'PE', 'HM'])->default('H');
            $table->date('fecha_nacimiento')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->index('employee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dependents');
    }
};