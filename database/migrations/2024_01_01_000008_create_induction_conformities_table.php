<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('induction_conformities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->enum('tipo', ['GENERAL', 'ESPECIFICA', 'SEGURIDAD']);
            $table->string('responsable', 100)->nullable();
            $table->date('fecha')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->index('employee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('induction_conformities');
    }
};