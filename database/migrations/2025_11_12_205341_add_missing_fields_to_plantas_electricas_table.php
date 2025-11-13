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
        Schema::table('plantas_electricas', function (Blueprint $table) {
            // Añadir campos faltantes para filtros con nombres consistentes al frontend
            $table->string('filtro_aire_cantidad')->nullable();
            $table->string('filtro_aire_referencia')->nullable();
            $table->string('filtro_aceite_cantidad')->nullable();
            $table->string('filtro_aceite_referencia')->nullable();
            $table->string('filtro_combustible_cantidad')->nullable();
            $table->string('filtro_combustible_referencia')->nullable();
            $table->string('filtro_separador_cantidad')->nullable();
            $table->string('filtro_separador_referencia')->nullable();
            $table->string('filtro_agua_cantidad')->nullable();
            $table->string('filtro_agua_referencia')->nullable();
            $table->string('cantidad_aceite')->nullable();
            $table->string('referencia_aceite')->nullable();
            $table->string('cantidad_refrigerante_liquido')->nullable();
            $table->string('referencia_refrigerante_liquido')->nullable();
            
            // Añadir campos faltantes de fotos durante
            $table->string('foto_siete_durante')->nullable();
            $table->string('pie_foto_siete_durante')->nullable();
            $table->string('foto_ocho_durante')->nullable();
            $table->string('pie_foto_ocho_durante')->nullable();
            $table->string('foto_nueve_durante')->nullable();
            $table->string('pie_foto_nueve_durante')->nullable();
            
            // Añadir campos faltantes varios
            $table->string('limpieza_general')->nullable();
            $table->string('correas_alternador')->nullable();
            $table->string('potencia')->nullable(); // Corregir typo de 'potenica'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plantas_electricas', function (Blueprint $table) {
            $table->dropColumn([
                'filtro_aire_cantidad',
                'filtro_aire_referencia',
                'filtro_aceite_cantidad',
                'filtro_aceite_referencia',
                'filtro_combustible_cantidad',
                'filtro_combustible_referencia',
                'filtro_separador_cantidad',
                'filtro_separador_referencia',
                'filtro_agua_cantidad',
                'filtro_agua_referencia',
                'cantidad_aceite',
                'referencia_aceite',
                'cantidad_refrigerante_liquido',
                'referencia_refrigerante_liquido',
                'foto_siete_durante',
                'pie_foto_siete_durante',
                'foto_ocho_durante',
                'pie_foto_ocho_durante',
                'foto_nueve_durante',
                'pie_foto_nueve_durante',
                'limpieza_general',
                'correas_alternador',
                'potencia',
            ]);
        });
    }
};
