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
        Schema::table('equipos', function (Blueprint $table) {
            $table->string('periodicidad')->nullable()->after('refrigerante_referencia')->comment('Periodicidad del mantenimiento: Semanal, Mensual, Trimestral, Semestral, Anual');
            $table->date('fecha_primer_mantenimiento')->nullable()->after('periodicidad')->comment('Fecha del primer mantenimiento programado');
            $table->json('proximas_fechas_mantenimiento')->nullable()->after('fecha_primer_mantenimiento')->comment('Array de fechas calculadas para los próximos mantenimientos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            $table->dropColumn(['periodicidad', 'fecha_primer_mantenimiento', 'proximas_fechas_mantenimiento']);
        });
    }
};
