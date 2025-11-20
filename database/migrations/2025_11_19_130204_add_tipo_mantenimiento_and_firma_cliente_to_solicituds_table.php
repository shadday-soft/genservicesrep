<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('solicituds', function (Blueprint $table) {
            $table->enum('tipo_mantenimiento', ['Mantenimiento Preventivo', 'Mantenimiento Correctivo'])->nullable()->after('actividad');
            $table->string('firma_cliente')->nullable()->after('tipo_mantenimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicituds', function (Blueprint $table) {
            $table->dropColumn(['tipo_mantenimiento', 'firma_cliente']);
        });
    }
};
