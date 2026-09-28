<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bonus_types', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('descripcion', 255)->nullable();
            $table->boolean('es_remunerativo')->default(true)->comment('1=remunerativo, 0=no remunerativo');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bonus_types');
    }
};