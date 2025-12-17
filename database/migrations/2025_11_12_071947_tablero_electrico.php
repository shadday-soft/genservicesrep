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
        Schema::create('tableros_electricos', function (Blueprint $table) {
            $table->id();

            // DATOS DEL CLIENTE

            $table->foreignUuid('solicitud_id')->constrained()->onDelete('cascade')->nullable();
            // DATOS DEL EQUIPO

            $table->text('tension_operacion')->nullable();
            $table->text('corriente_nominal')->nullable();
            $table->text('elemento_maniobra')->nullable();
            $table->text('fabricante')->nullable();
            $table->text('tipo_aplicacion')->nullable();
            $table->text('control_ats')->nullable();
            $table->text('tipo_servicio')->nullable();
            $table->text('horometro')->nullable();

            // OBSERVACIONES INICIALES

            $table->text('observaciones_iniciales')->nullable();

            // CHECK LIST

            $table->text('gabinete')->nullable();
            $table->text('puertas')->nullable();
            $table->text('cerraduras')->nullable();
            $table->text('bisagras')->nullable();
            $table->text('limpieza_general')->nullable();
            $table->text('pilotos_indicadores')->nullable();
            $table->text('selectores')->nullable();
            $table->text('reles')->nullable();
            $table->text('temporizadores')->nullable();
            $table->text('contactores')->nullable();
            $table->text('interruptores')->nullable();
            $table->text('conexiones_control')->nullable();
            $table->text('conexiones_potencia')->nullable();
            $table->text('barraje_potencia')->nullable();
            $table->text('barraje_neutros')->nullable();
            $table->text('barraje_tierras')->nullable();
            $table->text('plc')->nullable();
            $table->text('ats')->nullable();
            $table->text('fuentes_auxiliares_check')->nullable();
            $table->text('capacitores')->nullable();
            $table->text('analizador_de_red')->nullable();

            // FOTOS ESTADO INICIAL

            $table->text('Foto_uno_antes')->nullable();
            $table->text('Foto_dos_antes')->nullable();
            $table->text('Foto_tres_antes')->nullable();

            // ACTIVIDAD REALIZADA

            $table->text('actividad_realizada')->nullable();

            // PRUEBAS CON EL EQUIPO EN OPERACION

            // TIEMPOS:

            $table->text('segundos_tdes')->nullable();
            $table->text('segundos_tdne')->nullable();
            $table->text('segundos_tdtp')->nullable();
            $table->text('segundos_tden')->nullable();
            $table->text('segundos_tdec')->nullable();

            // AJUSTES:

            $table->text('alto_voltaje')->nullable();
            $table->text('bajo_voltaje')->nullable();
            $table->text('alta_frecuencia')->nullable();
            $table->text('baja_frecuencia')->nullable();
            $table->text('sobre_carga')->nullable();
            $table->text('sobre_corriente')->nullable();

            // TEMPERATURA:

            $table->text('cables_potencia')->nullable();
            $table->text('terminales')->nullable();
            $table->text('cuepo_contactores')->nullable();
            $table->text('cuerpo_interruptores')->nullable();
            $table->text('transformadores')->nullable();
            $table->text('punto_mas_caliente')->nullable();

            // OBSERVACIONES

            $table->text('observaciones_pruebas')->nullable();
            $table->text('pruebas_con_carga')->nullable();

            // VOLTAJE:

            $table->text('l1_n')->nullable();
            $table->text('l2_n')->nullable();
            $table->text('l3_n')->nullable();

            // FRECUENCIA

            $table->text('hz')->nullable();

            // KW

            $table->text('l1_kw')->nullable();
            $table->text('l2_kw')->nullable();
            $table->text('l3_kw')->nullable();
            $table->text('avg_kw')->nullable();

            // CORRIENTE

            $table->text('l1_corriente')->nullable();
            $table->text('l2_corriente')->nullable();
            $table->text('l3_corriente')->nullable();

            // FACTOR P

            $table->text('pf')->nullable();

            // KVA

            $table->text('l1_kva')->nullable();
            $table->text('l2_kva')->nullable();
            $table->text('l3_kva')->nullable();
            $table->text('avg_kva')->nullable();

            // FOTOS DURANTE

            $table->text('foto_uno_durante')->nullable();
            $table->text('foto_dos_durante')->nullable();
            $table->text('foto_tres_durante')->nullable();
            $table->text('foto_cuatro_durante')->nullable();
            $table->text('foto_cinco_durante')->nullable();
            $table->text('foto_seis_durante')->nullable();
            $table->text('foto_siete_durante')->nullable();
            $table->text('foto_ocho_durante')->nullable();
            $table->text('foto_nueve_durante')->nullable();

            // RECOMENDACIONES

            $table->text('recomendaciones')->nullable();

            // POSICION DE INSTRUMENTOS AL CONCLUIR EL SERVICIO

            $table->text('control')->nullable();
            $table->text('selector')->nullable();
            $table->text('fuentes_auxiliares_posicion')->nullable();

            // FOTOS DESPUES

            $table->text('foto_uno_despues')->nullable();
            $table->text('foto_dos_despues')->nullable();
            $table->text('foto_tres_despues')->nullable();

            // FICHA DE FIRMAS Y FECHAS

            $table->date('fecha_solicitud')->nullable();
            $table->string('llegada_tecnico')->nullable();
            $table->string('salida_tecnico')->nullable();

            $table->text('nombre_tecnico')->nullable();
            $table->text('cedula_tecnico')->nullable();
            $table->text('firma_tecnico')->nullable();
            $table->text('nombre_cliente')->nullable();
            $table->text('cedula_cliente')->nullable();
            $table->text('firma_cliente')->nullable();
            $table->text('calificacion_servicio')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tableros_electricos');
    }
};
