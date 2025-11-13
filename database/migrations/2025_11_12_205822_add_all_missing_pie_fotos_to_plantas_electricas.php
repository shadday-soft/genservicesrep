<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('plantas_electricas', function (Blueprint $table) {
            // Verificar y añadir campos pie_foto faltantes solo si no existen
            if (! Schema::hasColumn('plantas_electricas', 'pie_foto_uno_antes')) {
                $table->string('pie_foto_uno_antes')->nullable();
            }
            if (! Schema::hasColumn('plantas_electricas', 'pie_foto_dos_antes')) {
                $table->string('pie_foto_dos_antes')->nullable();
            }
            if (! Schema::hasColumn('plantas_electricas', 'pie_foto_tres_antes')) {
                $table->string('pie_foto_tres_antes')->nullable();
            }

            if (! Schema::hasColumn('plantas_electricas', 'pie_foto_uno_durante')) {
                $table->string('pie_foto_uno_durante')->nullable();
            }
            if (! Schema::hasColumn('plantas_electricas', 'pie_foto_dos_durante')) {
                $table->string('pie_foto_dos_durante')->nullable();
            }
            if (! Schema::hasColumn('plantas_electricas', 'pie_foto_tres_durante')) {
                $table->string('pie_foto_tres_durante')->nullable();
            }
            if (! Schema::hasColumn('plantas_electricas', 'pie_foto_cuatro_durante')) {
                $table->string('pie_foto_cuatro_durante')->nullable();
            }
            if (! Schema::hasColumn('plantas_electricas', 'pie_foto_cinco_durante')) {
                $table->string('pie_foto_cinco_durante')->nullable();
            }
            if (! Schema::hasColumn('plantas_electricas', 'pie_foto_seis_durante')) {
                $table->string('pie_foto_seis_durante')->nullable();
            }
            if (! Schema::hasColumn('plantas_electricas', 'pie_foto_siete_durante')) {
                $table->string('pie_foto_siete_durante')->nullable();
            }
            if (! Schema::hasColumn('plantas_electricas', 'pie_foto_ocho_durante')) {
                $table->string('pie_foto_ocho_durante')->nullable();
            }
            if (! Schema::hasColumn('plantas_electricas', 'pie_foto_nueve_durante')) {
                $table->string('pie_foto_nueve_durante')->nullable();
            }

            if (! Schema::hasColumn('plantas_electricas', 'pie_foto_uno_despues')) {
                $table->string('pie_foto_uno_despues')->nullable();
            }
            if (! Schema::hasColumn('plantas_electricas', 'pie_foto_dos_despues')) {
                $table->string('pie_foto_dos_despues')->nullable();
            }
            if (! Schema::hasColumn('plantas_electricas', 'pie_foto_tres_despues')) {
                $table->string('pie_foto_tres_despues')->nullable();
            }
        });

        // NOTA: Los siguientes statements están comentados porque algunas columnas no existen en la tabla
        // y causan errores durante las migraciones de test. Descomentar solo si las columnas existen.
        /*
        // Hacer nullable los campos text que no lo son
        DB::statement('ALTER TABLE plantas_electricas MODIFY observaciones_iniciales TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY nivel_aceite TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY nivel_refrigerante TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY nivel_combustible TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY capacidad_tanque TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY fugas TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY mangueras TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY sellos TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY tuberias TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY radiador TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY guardas TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY correas_ventilador TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY amortiguadores TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY precalentador_estado_inicial TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY bateria TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY nivel_electrolito TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY voltaje_bateria TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY estado_cargador TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY voltaje_cargador TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY voltaje_alternador TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY tipo_control TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY conexiones_control TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY conexiones_potencia TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY estado_generador TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY cantidad_filtro_aire TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY referencia_filtro_aire TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY cantidad_filtro_aceite TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY referencia_filtro_aceite TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY cantidad_filtro_combustible TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY referencia_filtro_combustible TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY cantidad_filtro_separador TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY referencia_filtro_separador TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY cantidad_filtro_agua TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY referencia_filtro_agua TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY cantidad_cantidad_aceite TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY referencia_cantidad_aceite TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY foto_uno_antes TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY foto_dos_antes TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY foto_tres_antes TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY control TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY transferencia TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY posicion_cargador TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY totalizador TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY precalentador_posicion TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY actividad_realizada TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY valor_presion_aceite TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY cantidad_presion_aceite TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY valor_temp_refrigerante TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY cantidad_temp_refrigerante TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY valor_temp_aceite TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY cantidad_temp_aceite TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY valor_temp_turbo TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY cantidad_temp_turbo TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY valor_rpm TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY cantidad_rpm TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY valor_voltaje_bateria TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY cantidad_voltaje_bateria TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY valor_caida_voltaje_bat TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY cantidad_caida_voltaje_bat TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY vac_fases_l1_l2 TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY vac_fases_l2_l3 TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY vac_fases_l1_l3 TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY vac_fase_n_l1n TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY vac_fase_n_l2n TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY vac_fase_n_l3n TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY amperios_l1 TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY amperios_l2 TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY amperios_l3 TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY potenica TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY hz TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY fp TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY baja_presion TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY alta_temperatura TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY bajo_nivel_refrigerante TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY bajo_voltaje_ac TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY foto_uno_durante TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY foto_dos_durante TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY foto_tres_durante TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY foto_cuatro_durante TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY foto_cinco_durante TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY foto_seis_durante TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY foto_siete_durante TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY foto_ocho_durante TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY foto_nueve_durante TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY foto_uno_despues TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY foto_dos_despues TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY foto_tres_despues TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY recomendaciones TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY nombre_tecnico TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY cedula_tecnico TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY firma_tecnico TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY nombre_cliente TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY cedula_cliente TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY firma_cliente TEXT NULL');
        DB::statement('ALTER TABLE plantas_electricas MODIFY calificacion_servicio TEXT NULL');
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plantas_electricas', function (Blueprint $table) {
            $table->dropColumn([
                'pie_foto_uno_antes',
                'pie_foto_dos_antes',
                'pie_foto_tres_antes',
                'pie_foto_uno_durante',
                'pie_foto_dos_durante',
                'pie_foto_tres_durante',
                'pie_foto_cuatro_durante',
                'pie_foto_cinco_durante',
                'pie_foto_seis_durante',
                'pie_foto_siete_durante',
                'pie_foto_ocho_durante',
                'pie_foto_nueve_durante',
                'pie_foto_uno_despues',
                'pie_foto_dos_despues',
                'pie_foto_tres_despues',
            ]);
        });
    }
};
