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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number', 20)->unique()->nullable();
            $table->string('password');
            $table->string('role')->default('customer');
            $table->string('enterprise_name')->nullable();
            $table->string('nit')->nullable();
=======
>>>>>>> 200ba4eda14599c192446dd1af7ae94e055c543d
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
