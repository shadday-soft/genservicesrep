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
            // $table->text('modelo_equipo')->nullable()->after('tipo_servicio');
            // $table->text('serie_equipo')->nullable()->after('modelo_equipo');
            // $table->text('modelo_motor')->nullable()->after('serie_equipo');
            // $table->text('serie_motor')->nullable()->after('modelo_motor');
            // $table->text('marca_motor')->nullable()->after('serie_motor');
            // $table->text('tension_operacion')->nullable()->after('marca_motor');
            $table->text('horometro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plantas_electricas', function (Blueprint $table) {
            $table->dropColumn([
                'modelo_equipo',
                'serie_equipo',
                'modelo_motor',
                'serie_motor',
                'marca_motor',
                'tension_operacion',
                'horometro',
            ]);
        });
    }
};
