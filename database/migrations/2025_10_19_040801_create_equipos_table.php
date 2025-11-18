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
        Schema::create('equipos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // --- DATOS GENERALES ---
            $table->string('nombre_equipo', 100)->comment('Nombre del equipo (Ej: Planta Eléctrica 1)');
            $table->foreignUuid('client_id')->nullable()->constrained()->onDelete('set null')->comment('Empresa a la que pertenece (Dropdown)');
            $table->foreignUuid('sucursal_id')->nullable()->constrained()->onDelete('set null')->comment('Sucursal o sede (Dropdown)');
            $table->text('detalles')->nullable()->comment('Campo de texto libre para detalles adicionales');
            $table->string('tipo_equipo', 50)->default('Planta Eléctrica')->comment('Tipo de equipo (Ej: Planta Eléctrica)');

            // --- DETALLES DE PLANTA ELÉCTRICA ---
            $table->string('potencia', 50)->nullable()->comment('Potencia del equipo (Ej: 100 kVA)');
            $table->string('modelo_equipo', 100)->nullable()->comment('Modelo del equipo');
            $table->string('modelo_motor', 100)->nullable()->comment('Modelo del motor');
            $table->string('tension_operacion', 50)->nullable()->comment('Tensión de operación (Ej: 220V/127V)');
            $table->string('serie_equipo', 100)->nullable()->comment('Número de serie del equipo');
            $table->string('serie_motor', 100)->nullable()->comment('Número de serie del motor');
            $table->string('marca_generador', 100)->nullable()->comment('Marca del generador');
            $table->string('horometro')->nullable()->comment('Horómetro actual del equipo (en horas)');
            $table->string('marca_motor', 100)->nullable()->comment('Marca del motor');

            // --- DETALLES DE TABLERO ELÉCTRICO ---
            $table->string('tablero_tipo', 100)->nullable()->default('Transferencia y distribución')->comment('Tipo de tablero (Ej: Transferencia y distribución)');
            $table->string('tablero_tension_operacion', 50)->nullable()->comment('Tensión de operación del tablero');
            $table->string('tablero_tipo_aplicacion', 100)->nullable()->comment('Tipo de aplicación del tablero');
            $table->string('tablero_fabricante', 100)->nullable()->comment('Fabricante del tablero');
            $table->string('tablero_corriente_nominal', 50)->nullable()->comment('Corriente nominal del tablero (Ej: 400A)');
            $table->string('tablero_elemento_maniobra', 100)->nullable()->comment('Elemento de maniobra principal del tablero');
            $table->string('tablero_controlador', 100)->nullable()->comment('Controlador del tablero (Ej: DSE7320)');

            // --- INSUMOS (Cantidades y Referencias) ---

            // Filtro de aire
            $table->string('filtro_aire_cantidad')->nullable();
            $table->string('filtro_aire_referencia', 100)->nullable();

            // Filtro de aceite
            $table->string('filtro_aceite_cantidad')->nullable();
            $table->string('filtro_aceite_referencia', 100)->nullable();

            // Filtro de combustible
            $table->string('filtro_combustible_cantidad')->nullable();
            $table->string('filtro_combustible_referencia', 100)->nullable();

            // Filtro separador
            $table->string('filtro_separador_cantidad')->nullable();
            $table->string('filtro_separador_referencia', 100)->nullable();

            // Filtro de agua
            $table->string('filtro_agua_cantidad')->nullable();
            $table->string('filtro_agua_referencia', 100)->nullable();

            // Filtro de aceite (Segundo campo de aceite, si aplica)
            $table->string('filtro_aceite_2_cantidad')->nullable()->comment('Campo adicional para filtro de aceite, si aplica');
            $table->string('filtro_aceite_2_referencia', 100)->nullable();

            // Cantidad de refrigerante líquido
            $table->string('refrigerante_cantidad')->nullable();
            $table->string('refrigerante_referencia', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
