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
        Schema::create('tecnicos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('foto')->nullable();
            $table->string('identificacion')->unique();
            $table->string('correo')->unique();
            $table->string('nombre_completo');
            $table->string('persona_contacto')->nullable();
            $table->enum('tipo_sangre', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->string('eps')->nullable();
            $table->string('fecha_nacimiento')->nullable();
            $table->string('fecha_inicio_contrato');
            $table->string('fecha_fin_contrato')->nullable();
            $table->enum('tipo_contrato', ['Indefinido', 'Fijo', 'Obra o labor', 'Prestación de servicios'])->default('Indefinido');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tecnicos');
    }
};
