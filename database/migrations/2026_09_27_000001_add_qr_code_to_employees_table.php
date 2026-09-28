<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('qr_code', 64)->unique()->nullable()->after('dni');
        });

        DB::table('employees')->whereNull('qr_code')->orderBy('id')->each(function ($employee) {
            do {
                $codigo = Str::random(20);
            } while (DB::table('employees')->where('qr_code', $codigo)->exists());

            DB::table('employees')->where('id', $employee->id)->update(['qr_code' => $codigo]);
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropUnique(['qr_code']);
            $table->dropColumn('qr_code');
        });
    }
};
