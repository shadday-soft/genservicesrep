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
        Schema::create('solicituds', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Relaciones
            $table->foreignUuid('client_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignUuid('sucursal_id')->constrained();
            $table->foreignUuid('equipo_id')->constrained();
            $table->foreignid('user_id')->nullable()->constrained()->onDelete('set null');
            // Campos principales
            $table->string('fecha_programada')->nullable();
            $table->string('quien_solicita')->nullable();
            $table->string('telefono')->nullable();
            $table->string('mail')->nullable();
            $table->string('ubicacion')->nullable();
            $table->enum('prioridad', ['Normal', 'Intermedio', 'Urgente'])->default('Normal');
            $table->text('detalles')->nullable();
            $table->enum('estado', ['Nueva', 'Proceso', 'Revisión', 'Finalizada', 'Anulada', 'Programada'])->default('Nueva');
            $table->string('actividad');
            $table->string('mantenimiento_id')->nullable();
            $table->string('fecha_mantenimiento')->nullable();

            $table->string('numero_orden')->unique()->nullable(); // Generado automáticamente
            // Información de mantenimiento
            // Datos de contacto

            // Fecha programada
            $table->string('orden_trabajo')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicituds');
    }
};
