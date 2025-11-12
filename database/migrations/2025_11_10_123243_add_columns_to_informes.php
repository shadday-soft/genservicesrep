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
            $table->foreignUuid('solicitud_id')->constrained()->onDelete('cascade');

            // DATOS DEL EQUIPO

            $table->string('tipo_servicio');
            $table->text('observaciones_iniciales');

            // ESTADO INICIAL

            $table->text('nivel_aceite');
            $table->text('nivel_refrigerante');
            $table->text('nivel_combustible');
            $table->text('capacidad_tanque');
            $table->text('fugas');
            $table->text('mangueras');
            $table->text('sellos');
            $table->text('tuberias');
            $table->text('radiador');
            $table->text('guardas');
            $table->text('correas_ventilador');
            $table->text('amortiguadores');
            $table->text('precalentador_estado_inicial');
            $table->text('bateria');
            $table->text('nivel_electrolito');
            $table->text('voltaje_bateria');
            $table->text('estado_cargador');
            $table->text('voltaje_cargador');
            $table->text('voltaje_alternador');
            $table->text('tipo_control');
            $table->text('conexiones_control');
            $table->text('conexiones_potencia');
            $table->text('estado_generador');
            $table->text('limpieza_generador');

            $table->text('cantidad_filtro_aire');
            $table->text('referencia_filtro_aire');
            $table->text('cantidad_filtro_aceite');
            $table->text('referencia_filtro_aceite');
            $table->text('cantidad_filtro_combustible');
            $table->text('referencia_filtro_combustible');
            $table->text('cantidad_filtro_separador');
            $table->text('referencia_filtro_separador');
            $table->text('cantidad_filtro_agua');
            $table->text('referencia_filtro_agua');
            $table->text('cantidad_cantidad_aceite');
            $table->text('referencia_cantidad_aceite');

            // FOTOS ANTES

            $table->text('foto_uno_antes');
            $table->text('foto_dos_antes');
            $table->text('foto_tres_antes');

            // POSICION DE INSTRUMENTOS AL CONCLUIR EL SERVICIOS

            $table->text('control');
            $table->text('transferencia');
            $table->text('posicion_cargador');
            $table->text('totalizador');
            $table->text('precalentador_posicion');

            // ACTIVIDAD REALIZADA

            $table->text('actividad_realizada');

            // PRUEBAS CON EQUIPO OPERANDO

            // MOTOR:

            $table->text('valor_presion_aceite');
            $table->text('cantidad_presion_aceite');

            $table->text('valor_temp_refrigerante');
            $table->text('cantidad_temp_refrigerante');

            $table->text('valor_temp_aceite');
            $table->text('cantidad_temp_aceite');

            $table->text('valor_temp_turbo');
            $table->text('cantidad_temp_turbo');

            $table->text('valor_rpm');
            $table->text('cantidad_rpm');

            $table->text('valor_voltaje_bateria');
            $table->text('cantidad_voltaje_bateria');

            $table->text('valor_caida_voltaje_bat');
            $table->text('cantidad_caida_voltaje_bat');

            // GENERADOR:

            $table->text('vac_fases_l1_l2');
            $table->text('vac_fases_l2_l3');
            $table->text('vac_fases_l1_l3');

            $table->text('vac_fase_n_l1n');
            $table->text('vac_fase_n_l2n');
            $table->text('vac_fase_n_l3n');

            $table->text('amperios_l1');
            $table->text('amperios_l2');
            $table->text('amperios_l3');

            $table->text('potenica');
            $table->text('hz');
            $table->text('fp');

            // PROTECCIONES

            $table->text('baja_presion');
            $table->text('alta_temperatura');
            $table->text('bajo_nivel_refrigerante');
            $table->text('bajo_voltaje_de_ac');

            // FOTOS DURANTE

            $table->text('foto_uno_durante');
            $table->text('foto_dos_durante');
            $table->text('foto_tres_durante');
            $table->text('foto_cuatro_durante');
            $table->text('foto_cinco_durante');
            $table->text('foto_seis_durante');

            // FOTOS DESPUES

            $table->text('foto_uno_despues');
            $table->text('foto_dos_despues');
            $table->text('foto_tres_despues');

            // RECOMENDACIONES

            $table->text('recomendaciones');

            // FICHA DE FIRMAS Y FECHAS

            $table->date('fecha_solicitud')->nullable();
            $table->date('llegada_tecnico')->nullable();
            $table->date('salida_tecnico')->nullable();

            $table->text('nombre_tecnico');
            $table->text('cedula_tecnico');
            $table->text('firma_tecnico');
            $table->text('nombre_cliente');
            $table->text('cedula_cliente');
            $table->text('firma_cliente');
            $table->text('calificacion_servicio');


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
