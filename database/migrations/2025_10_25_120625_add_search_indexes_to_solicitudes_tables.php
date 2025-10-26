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
        // Índices para la tabla solicituds
        Schema::table('solicituds', function (Blueprint $table) {
            $table->index('numero_orden', 'idx_solicituds_numero_orden');
            $table->index('estado', 'idx_solicituds_estado');
            $table->index('prioridad', 'idx_solicituds_prioridad');
            $table->index('created_at', 'idx_solicituds_created_at');
            $table->index(['client_id', 'estado'], 'idx_solicituds_client_estado');
            $table->index(['sucursal_id', 'estado'], 'idx_solicituds_sucursal_estado');
            $table->index('quien_solicita', 'idx_solicituds_quien_solicita');
            $table->index('telefono', 'idx_solicituds_telefono');
            $table->index('mail', 'idx_solicituds_mail');
        });

        // Índices para la tabla clients
        Schema::table('clients', function (Blueprint $table) {
            $table->index('enterprise_name', 'idx_clients_enterprise_name');
        });

        // Índices para la tabla sucursals
        Schema::table('sucursals', function (Blueprint $table) {
            $table->index('name', 'idx_sucursals_name');
        });

        // Índices para la tabla equipos
        Schema::table('equipos', function (Blueprint $table) {
            $table->index('nombre_equipo', 'idx_equipos_nombre');
            $table->index('tipo_equipo', 'idx_equipos_tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar índices de solicituds
        Schema::table('solicituds', function (Blueprint $table) {
            $table->dropIndex('idx_solicituds_numero_orden');
            $table->dropIndex('idx_solicituds_estado');
            $table->dropIndex('idx_solicituds_prioridad');
            $table->dropIndex('idx_solicituds_created_at');
            $table->dropIndex('idx_solicituds_client_estado');
            $table->dropIndex('idx_solicituds_sucursal_estado');
            $table->dropIndex('idx_solicituds_quien_solicita');
            $table->dropIndex('idx_solicituds_telefono');
            $table->dropIndex('idx_solicituds_mail');
        });

        // Eliminar índices de clients
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex('idx_clients_enterprise_name');
        });

        // Eliminar índices de sucursals
        Schema::table('sucursals', function (Blueprint $table) {
            $table->dropIndex('idx_sucursals_name');
        });

        // Eliminar índices de equipos
        Schema::table('equipos', function (Blueprint $table) {
            $table->dropIndex('idx_equipos_nombre');
            $table->dropIndex('idx_equipos_tipo');
        });
    }
};
