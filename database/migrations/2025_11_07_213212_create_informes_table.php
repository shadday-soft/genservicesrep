<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Desactivar strict mode temporalmente
        DB::statement('SET SESSION innodb_strict_mode=OFF');
        
        // Crear tabla con ROW_FORMAT=DYNAMIC para permitir más columnas
        DB::statement("
            CREATE TABLE informes ( 
                id CHAR(36) PRIMARY KEY,
                tipo_servicio VARCHAR(50) NOT NULL DEFAULT 'Mantenimiento',
                observaciones_iniciales TEXT NULL,
                
                -- Estado inicial
                nivel_aceite VARCHAR(10) NULL,
                nivel_refrigerante VARCHAR(10) NULL,
                nivel_combustible VARCHAR(50) NULL,
                capacidad_tanque VARCHAR(50) NULL,
                fugas TEXT NULL,
                mangueras VARCHAR(10) NULL,
                sellos VARCHAR(10) NULL,
                tuberias VARCHAR(10) NULL,
                radiador VARCHAR(10) NULL,
                guardas VARCHAR(10) NULL,
                correas_ventilador VARCHAR(10) NULL,
                correas_alternador VARCHAR(10) NULL,
                amortiguadores VARCHAR(10) NULL,
                precalentador_estado_inicial VARCHAR(10) NULL,
                bateria VARCHAR(10) NULL,
                nivel_electrolito VARCHAR(10) NULL,
                voltaje_bateria_estado VARCHAR(10) NULL,
                estado_cargador VARCHAR(10) NULL,
                voltaje_cargador VARCHAR(50) NULL,
                tipo_control VARCHAR(100) NULL,
                voltaje_alternador VARCHAR(50) NULL,
                conexiones_control VARCHAR(10) NULL,
                conexiones_potencia VARCHAR(10) NULL,
                limpieza_general VARCHAR(10) NULL,
                
                -- Fotos antes
                foto_uno_antes TEXT NULL,
                foto_dos_antes TEXT NULL,
                foto_tres_antes TEXT NULL,
                
                -- Actividad realizada
                actividad_realizada TEXT NULL,
                
                -- Pruebas motor
                presion_aceite_valor VARCHAR(50) NULL,
                presion_aceite_unidad VARCHAR(20) NULL,
                temp_refrigerante_valor VARCHAR(50) NULL,
                temp_refrigerante_unidad VARCHAR(20) NULL,
                temp_aceite_valor VARCHAR(50) NULL,
                temp_aceite_unidad VARCHAR(20) NULL,
                temp_turbo_valor VARCHAR(50) NULL,
                temp_turbo_unidad VARCHAR(20) NULL,
                rpm_valor VARCHAR(50) NULL,
                rpm_unidad VARCHAR(20) NULL,
                voltaje_bateria_valor VARCHAR(50) NULL,
                voltaje_bateria_unidad VARCHAR(20) NULL,
                caida_voltaje_bat_valor VARCHAR(50) NULL,
                caida_voltaje_bat_unidad VARCHAR(20) NULL,
                
                -- Generador
                vac_fases_l1_l2 VARCHAR(50) NULL,
                vac_fases_l2_l3 VARCHAR(50) NULL,
                vac_fases_l1_l3 VARCHAR(50) NULL,
                amperios_l1 VARCHAR(50) NULL,
                amperios_l2 VARCHAR(50) NULL,
                amperios_l3 VARCHAR(50) NULL,
                vac_fase_n_l1 VARCHAR(50) NULL,
                vac_fase_n_l2 VARCHAR(50) NULL,
                vac_fase_n_l3 VARCHAR(50) NULL,
                potencia VARCHAR(50) NULL,
                hz VARCHAR(50) NULL,
                fp VARCHAR(50) NULL,
                
                -- Protecciones
                baja_presion VARCHAR(50) NULL,
                alta_temperatura VARCHAR(50) NULL,
                bajo_nivel_regrigerante VARCHAR(50) NULL,
                bajo_voltaje_ac VARCHAR(50) NULL,
                
                -- Fotos durante
                foto_uno_durante TEXT NULL,
                foto_dos_durante TEXT NULL,
                foto_tres_durante TEXT NULL,
                foto_cuatro_durante TEXT NULL,
                foto_cinco_durante TEXT NULL,
                foto_seis_durante TEXT NULL,
                foto_siete_durante TEXT NULL,
                foto_ocho_durante TEXT NULL,
                foto_nueve_durante TEXT NULL,
                
                -- Recomendaciones
                recomendaciones TEXT NULL,
                
                -- Técnico
                llegada_tecnico DATETIME NULL,
                salida_tecnico DATETIME NULL,
                
                -- Calificación
                calificacion_servicio VARCHAR(20) NULL,
                
                -- Posición instrumentos
                control VARCHAR(10) NULL,
                transferencia VARCHAR(10) NULL,
                posicion_cargador VARCHAR(10) NULL,
                totalizador VARCHAR(10) NULL,
                precalentador_posicion VARCHAR(10) NULL,
                
                -- Fotos después
                foto_uno_despues TEXT NULL,
                foto_dos_despues TEXT NULL,
                foto_tres_despues TEXT NULL,
                
                -- Firmas
                firma_tecnico LONGTEXT NULL,
                nombre_tecnico VARCHAR(100) NULL,
                cedula_tecnico VARCHAR(50) NULL,
                firma_cliente LONGTEXT NULL,
                nombre_cliente VARCHAR(100) NULL,
                cedula_cliente VARCHAR(50) NULL,
                
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC
        ");
        
        // Restaurar strict mode
        DB::statement('SET SESSION innodb_strict_mode=ON');
    }    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informes');
    }
};
