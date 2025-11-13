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
            $table->string('pie_foto_siete_durante')->nullable()->after('foto_siete_durante');
            $table->string('pie_foto_ocho_durante')->nullable()->after('foto_ocho_durante');
            $table->string('pie_foto_nueve_durante')->nullable()->after('foto_nueve_durante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plantas_electricas', function (Blueprint $table) {
            $table->dropColumn([
                'pie_foto_siete_durante',
                'pie_foto_ocho_durante',
                'pie_foto_nueve_durante',
            ]);
        });
    }
};
