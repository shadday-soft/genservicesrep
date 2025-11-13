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
            // Hacer nullable todos los campos de text que no lo son
            $table->text('nivel_combustible')->nullable()->change();
            $table->text('capacidad_tanque')->nullable()->change();
            $table->text('fugas')->nullable()->change();
            $table->text('mangueras')->nullable()->change();
            $table->text('sellos')->nullable()->change();
            $table->text('tuberias')->nullable()->change();
            $table->text('radiador')->nullable()->change();
            $table->text('guardas')->nullable()->change();
            $table->text('correas_ventilador')->nullable()->change();
            $table->text('amortiguadores')->nullable()->change();
            $table->text('precalentador_estado_inicial')->nullable()->change();
            $table->text('bateria')->nullable()->change();
            $table->text('nivel_electrolito')->nullable()->change();
            $table->text('voltaje_bateria')->nullable()->change();
            $table->text('estado_cargador')->nullable()->change();
            $table->text('voltaje_cargador')->nullable()->change();
            $table->text('voltaje_alternador')->nullable()->change();
            $table->text('tipo_control')->nullable()->change();
            $table->text('conexiones_control')->nullable()->change();
            $table->text('conexiones_potencia')->nullable()->change();
            $table->text('estado_generador')->nullable()->change();
            $table->text('cantidad_filtro_aire')->nullable()->change();
            $table->text('referencia_filtro_aire')->nullable()->change();
            $table->text('cantidad_filtro_aceite')->nullable()->change();
            $table->text('referencia_filtro_aceite')->nullable()->change();
            $table->text('cantidad_filtro_combustible')->nullable()->change();
            $table->text('referencia_filtro_combustible')->nullable()->change();
            $table->text('cantidad_filtro_separador')->nullable()->change();
            $table->text('referencia_filtro_separador')->nullable()->change();
            $table->text('cantidad_filtro_agua')->nullable()->change();
            $table->text('referencia_filtro_agua')->nullable()->change();
            $table->text('cantidad_cantidad_aceite')->nullable()->change();
            $table->text('referencia_cantidad_aceite')->nullable()->change();
            $table->text('foto_uno_antes')->nullable()->change();
            $table->text('pie_foto_uno_antes')->nullable()->change();
            $table->text('foto_dos_antes')->nullable()->change();
            $table->text('pie_foto_dos_antes')->nullable()->change();
            $table->text('foto_tres_antes')->nullable()->change();
            $table->text('pie_foto_tres_antes')->nullable()->change();
            $table->text('control')->nullable()->change();
            $table->text('transferencia')->nullable()->change();
            $table->text('posicion_cargador')->nullable()->change();
            $table->text('totalizador')->nullable()->change();
            $table->text('precalentador_posicion')->nullable()->change();
            $table->text('actividad_realizada')->nullable()->change();
            $table->text('valor_presion_aceite')->nullable()->change();
            $table->text('cantidad_presion_aceite')->nullable()->change();
            $table->text('valor_temp_refrigerante')->nullable()->change();
            $table->text('cantidad_temp_refrigerante')->nullable()->change();
            $table->text('valor_temp_aceite')->nullable()->change();
            $table->text('cantidad_temp_aceite')->nullable()->change();
            $table->text('valor_temp_turbo')->nullable()->change();
            $table->text('cantidad_temp_turbo')->nullable()->change();
            $table->text('valor_rpm')->nullable()->change();
            $table->text('cantidad_rpm')->nullable()->change();
            $table->text('valor_voltaje_bateria')->nullable()->change();
            $table->text('cantidad_voltaje_bateria')->nullable()->change();
            $table->text('valor_caida_voltaje_bat')->nullable()->change();
            $table->text('cantidad_caida_voltaje_bat')->nullable()->change();
            $table->text('vac_fases_l1_l2')->nullable()->change();
            $table->text('vac_fases_l2_l3')->nullable()->change();
            $table->text('vac_fases_l1_l3')->nullable()->change();
            $table->text('vac_fase_n_l1n')->nullable()->change();
            $table->text('vac_fase_n_l2n')->nullable()->change();
            $table->text('vac_fase_n_l3n')->nullable()->change();
            $table->text('amperios_l1')->nullable()->change();
            $table->text('amperios_l2')->nullable()->change();
            $table->text('amperios_l3')->nullable()->change();
            $table->text('potenica')->nullable()->change();
            $table->text('hz')->nullable()->change();
            $table->text('fp')->nullable()->change();
            $table->text('baja_presion')->nullable()->change();
            $table->text('alta_temperatura')->nullable()->change();
            $table->text('bajo_nivel_refrigerante')->nullable()->change();
            $table->text('bajo_voltaje_de_ac')->nullable()->change();
            $table->text('foto_uno_durante')->nullable()->change();
            $table->text('pie_foto_uno_durante')->nullable()->change();
            $table->text('foto_dos_durante')->nullable()->change();
            $table->text('pie_foto_dos_durante')->nullable()->change();
            $table->text('foto_tres_durante')->nullable()->change();
            $table->text('pie_foto_tres_durante')->nullable()->change();
            $table->text('foto_cuatro_durante')->nullable()->change();
            $table->text('pie_foto_cuatro_durante')->nullable()->change();
            $table->text('foto_cinco_durante')->nullable()->change();
            $table->text('pie_foto_cinco_durante')->nullable()->change();
            $table->text('foto_seis_durante')->nullable()->change();
            $table->text('pie_foto_seis_durante')->nullable()->change();
            $table->text('foto_uno_despues')->nullable()->change();
            $table->text('pie_foto_uno_despues')->nullable()->change();
            $table->text('foto_dos_despues')->nullable()->change();
            $table->text('pie_foto_dos_despues')->nullable()->change();
            $table->text('foto_tres_despues')->nullable()->change();
            $table->text('pie_foto_tres_despues')->nullable()->change();
            $table->text('recomendaciones')->nullable()->change();
            $table->text('nombre_tecnico')->nullable()->change();
            $table->text('cedula_tecnico')->nullable()->change();
            $table->text('firma_tecnico')->nullable()->change();
            $table->text('nombre_cliente')->nullable()->change();
            $table->text('cedula_cliente')->nullable()->change();
            $table->text('firma_cliente')->nullable()->change();
            $table->text('calificacion_servicio')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plantas_electricas', function (Blueprint $table) {
            // No revertimos porque podría causar pérdida de datos
        });
    }
};
