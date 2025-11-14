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
        Schema::create('plantas_electricas', function (Blueprint $table) {
            $table->id();

            // DATOS DEL CLIENTE
            $table->foreignUuid('solicitud_id')->nullable()->constrained()->onDelete('cascade')->nullable();

            // DATOS DEL EQUIPO

            $table->string('tipo_servicio')->nullable();
            $table->text('observaciones_iniciales')->nullable();

            // ESTADO INICIAL

            $table->text('nivel_aceite')->nullable();
            $table->text('nivel_refrigerante')->nullable();
            $table->text('nivel_combustible')->nullable();
            $table->text('capacidad_tanque')->nullable();
            $table->text('fugas')->nullable();
            $table->text('mangueras')->nullable();
            $table->text('sellos')->nullable();
            $table->text('potencia')->nullable();
            $table->text('tuberias')->nullable();
            $table->text('radiador')->nullable();
            $table->text('guardas')->nullable();
            $table->text('correas_ventilador')->nullable(); 
            $table->text('correas_alternador')->nullable();
            $table->text('amortiguadores')->nullable();
            $table->text('precalentador_estado_inicial')->nullable();
            $table->text('bateria')->nullable();
            $table->text('nivel_electrolito')->nullable();
            $table->text('voltaje_bateria')->nullable();
            $table->text('estado_cargador')->nullable();
            $table->text('voltaje_cargador')->nullable();
            $table->text('voltaje_alternador')->nullable();
            $table->text('tipo_control')->nullable();
            $table->text('conexiones_control')->nullable();
            $table->text('conexiones_potencia')->nullable();
            $table->text('estado_generador')->nullable();
            $table->text('limpieza_general')->nullable();

            $table->text('cantidad_filtro_aire')->nullable();
            $table->text('referencia_filtro_aire')->nullable();
            $table->text('cantidad_filtro_aceite')->nullable();
            $table->text('referencia_filtro_aceite')->nullable();
            $table->text('cantidad_filtro_combustible')->nullable();
            $table->text('referencia_filtro_combustible')->nullable();
            $table->text('cantidad_filtro_separador')->nullable();
            $table->text('referencia_filtro_separador')->nullable();
            $table->text('cantidad_filtro_agua')->nullable();
            $table->text('referencia_filtro_agua')->nullable();
            $table->text('cantidad_cantidad_aceite')->nullable();
            $table->text('referencia_cantidad_aceite')->nullable();

            // FOTOS ANTES

            $table->text('foto_uno_antes')->nullable();
            $table->text('pie_foto_uno_antes')->nullable();
            $table->text('foto_dos_antes')->nullable();
            $table->text('pie_foto_dos_antes')->nullable();
            $table->text('foto_tres_antes')->nullable();
            $table->text('pie_foto_tres_antes')->nullable();

            // POSICION DE INSTRUMENTOS AL CONCLUIR EL SERVICIOS

            $table->text('control')->nullable();
            $table->text('transferencia')->nullable();
            $table->text('posicion_cargador')->nullable();
            $table->text('totalizador')->nullable();
            $table->text('precalentador_posicion')->nullable();

            // ACTIVIDAD REALIZADA

            $table->text('actividad_realizada')->nullable();

            // PRUEBAS CON EQUIPO OPERANDO

            // MOTOR:

            $table->text('valor_presion_aceite')->nullable();
            $table->text('cantidad_presion_aceite')->nullable();

            $table->text('valor_temp_refrigerante')->nullable();
            $table->text('cantidad_temp_refrigerante')->nullable();

            $table->text('valor_temp_aceite')->nullable();
            $table->text('cantidad_temp_aceite')->nullable();

            $table->text('valor_temp_turbo')->nullable();
            $table->text('cantidad_temp_turbo')->nullable();

            $table->text('valor_rpm')->nullable();
            $table->text('cantidad_rpm')->nullable();

            $table->text('valor_voltaje_bateria')->nullable();
            $table->text('cantidad_voltaje_bateria')->nullable();

            $table->text('valor_caida_voltaje_bat')->nullable();
            $table->text('cantidad_caida_voltaje_bat')->nullable();

            // GENERADOR:

            $table->text('vac_fases_l1_l2')->nullable();
            $table->text('vac_fases_l2_l3')->nullable();
            $table->text('vac_fases_l1_l3')->nullable();

            $table->text('vac_fase_n_l1n')->nullable();
            $table->text('vac_fase_n_l2n')->nullable();
            $table->text('vac_fase_n_l3n')->nullable();

            $table->text('amperios_l1')->nullable();
            $table->text('amperios_l2')->nullable();
            $table->text('amperios_l3')->nullable();

            $table->text('potenica')->nullable();
            $table->text('hz')->nullable();
            $table->text('fp')->nullable();

            // PROTECCIONES

            $table->text('baja_presion')->nullable();
            $table->text('alta_temperatura')->nullable();
            $table->text('bajo_nivel_refrigerante')->nullable();
            $table->text('bajo_voltaje_ac')->nullable();

            // FOTOS DURANTE

            $table->text('foto_uno_durante')->nullable();
            $table->text('pie_foto_uno_durante')->nullable();
            $table->text('foto_dos_durante')->nullable();
            $table->text('pie_foto_dos_durante')->nullable();
            $table->text('foto_tres_durante')->nullable();
            $table->text('pie_foto_tres_durante')->nullable();
            $table->text('foto_cuatro_durante')->nullable();
            $table->text('pie_foto_cuatro_durante')->nullable();
            $table->text('foto_cinco_durante')->nullable();
            $table->text('pie_foto_cinco_durante')->nullable();
            $table->text('foto_seis_durante')->nullable();
            $table->text('pie_foto_seis_durante')->nullable();
            $table->text('foto_siete_durante')->nullable();
            $table->text('pie_foto_siete_durante')->nullable();
            $table->text('foto_ocho_durante')->nullable();
            $table->text('pie_foto_ocho_durante')->nullable();
            $table->text('foto_nueve_durante')->nullable();
            $table->text('pie_foto_nueve_durante')->nullable();

            // FOTOS DESPUES

            $table->text('foto_uno_despues')->nullable();
            $table->text('pie_foto_uno_despues')->nullable();
            $table->text('foto_dos_despues')->nullable();
            $table->text('pie_foto_dos_despues')->nullable();
            $table->text('foto_tres_despues')->nullable();
            $table->text('pie_foto_tres_despues')->nullable();

            // RECOMENDACIONES

            $table->text('recomendaciones')->nullable();

            // FICHA DE FIRMAS Y FECHAS

            $table->date('fecha_solicitud')->nullable();
            $table->date('llegada_tecnico')->nullable();
            $table->date('salida_tecnico')->nullable();

            $table->text('nombre_tecnico')->nullable();
            $table->text('cedula_tecnico')->nullable();
            $table->text('firma_tecnico')->nullable();
            $table->text('nombre_cliente')->nullable();
            $table->text('cedula_cliente')->nullable();
            $table->text('firma_cliente')->nullable();
            $table->text('calificacion_servicio')->nullable();

            // Continúa agregando campos según tu modelo de datos
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('informes', function (Blueprint $table) {
            //
        });
    }
};
