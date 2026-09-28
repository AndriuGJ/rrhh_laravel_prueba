<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained('payrolls')->onDelete('cascade');
            $table->string('concepto', 50);
            $table->enum('tipo', ['COMPUTABLE', 'NO_COMPUTABLE'])->default('COMPUTABLE');
            $table->decimal('monto', 10, 2)->default(0);
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->index('payroll_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_discounts');
    }
};