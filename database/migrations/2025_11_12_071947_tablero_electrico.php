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

            $table->foreignUuid('solicitud_id')->constrained()->onDelete('cascade');
            // DATOS DEL EQUIPO

            $table->text('tension_operacion');
            $table->text('corriente_nominal');
            $table->text('elemento_maniobra');
            $table->text('fabricante');
            $table->text('tipo_aplicacion');
            $table->text('control_ats');
            $table->text('tipo_servicio');
            $table->text('horometro')->nullable();
            
            // OBSERVACIONES INICIALES

            $table->text('observaciones_iniciales');

            // CHECK LIST

            $table->text('gabinete');
            $table->text('puertas');
            $table->text('cerraduras');
            $table->text('bisagras');
            $table->text('limpieza_general');
            $table->text('pilotos_indicadores');
            $table->text('selectores');
            $table->text('reles');
            $table->text('temporizadores');
            $table->text('contactores');
            $table->text('interruptores');
            $table->text('conexiones_control');
            $table->text('conexiones_potencia');
            $table->text('barraje_potencia');
            $table->text('barraje_neutros');
            $table->text('barraje_tierras');
            $table->text('plc');
            $table->text('ats');
            $table->text('fuentes_auxiliares_check');
            $table->text('capacitores');
            $table->text('analizador_de_red');

            // FOTOS ESTADO INICIAL

            $table->text('Foto_uno_antes');
            $table->text('Foto_dos_antes');
            $table->text('Foto_tres_antes');

            // ACTIVIDAD REALIZADA

            $table->text('actividad_realizada');

            // PRUEBAS CON EL EQUIPO EN OPERACION

                // TIEMPOS:
            
                $table->text('segundos_tdes');
                $table->text('segundos_tdne');
                $table->text('segundos_tdtp');
                $table->text('segundos_tden');
                $table->text('segundos_tdec');

                // AJUSTES:

                $table->text('alto_voltaje');
                $table->text('bajo_voltaje');
                $table->text('alta_frecuencia');
                $table->text('baja_frecuencia');
                $table->text('sobre_carga');
                $table->text('sobre_corriente');

                // TEMPERATURA:

                $table->text('cables_potencia');
                $table->text('terminales');
                $table->text('cuepo_contactores');
                $table->text('cuerpo_interruptores');
                $table->text('transformadores');
                $table->text('punto_mas_caliente');

                // OBSERVACIONES

                $table->text('observaciones_pruebas');
                $table->text('pruebas_con_carga');

                // VOLTAJE:

                $table->text('l1_n');
                $table->text('l2_n');
                $table->text('l3_n');

                // FRECUENCIA

                $table->text('hz');

                // KW

                $table->text('l1_kw');
                $table->text('l2_kw');
                $table->text('l3_kw');
                $table->text('avg_kw');

                // CORRIENTE

                $table->text('l1_corriente');
                $table->text('l2_corriente');
                $table->text('l3_corriente');

                // FACTOR P

                $table->text('pf');

                // KVA

                $table->text('l1_kva');
                $table->text('l2_kva');
                $table->text('l3_kva');
                $table->text('avg_kva');

            // FOTOS DURANTE

            $table->text('foto_uno_durante');
            $table->text('foto_dos_durante');
            $table->text('foto_tres_durante');
            $table->text('foto_cuatro_durante');
            $table->text('foto_cinco_durante');
            $table->text('foto_seis_durante');

            // RECOMENDACIONES

            $table->text('recomendaciones');

            // POSICION DE INSTRUMENTOS AL CONCLUIR EL SERVICIO

            $table->text('control');
            $table->text('selector');
            $table->text('fuentes_auxiliares_posicion');

            // FOTOS DESPUES

            $table->text('foto_uno_despues');
            $table->text('foto_dos_despues');
            $table->text('foto_tres_despues');

            // FICHA DE FIRMAS Y FECHAS

            $table->date('fecha_solicitud')->nullable();
            $table->string('llegada_tecnico')->nullable();
            $table->string('salida_tecnico')->nullable();

            $table->text('nombre_tecnico');
            $table->text('cedula_tecnico');
            $table->text('firma_tecnico');
            $table->text('nombre_cliente');
            $table->text('cedula_cliente');
            $table->text('firma_cliente');
            $table->text('calificacion_servicio');

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
