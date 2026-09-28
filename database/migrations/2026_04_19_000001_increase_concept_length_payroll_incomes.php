<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payroll_incomes', function (Blueprint $table) {
            $table->string('concepto', 100)->change();
        });
    }

    public function down(): void
    {
        Schema::table('payroll_incomes', function (Blueprint $table) {
            $table->string('concepto', 50)->change();
        });
    }
};