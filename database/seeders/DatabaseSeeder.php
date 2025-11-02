<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Client::factory(10)->create();
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
            'role' => 'Administrador',
        ]);

        User::create([
            'name' => 'Gen Services',
            'email' => 'genservices@outlook.com',
            'password' => bcrypt('Soport3e@1978*'),
            'role' => 'Administrador',
        ]);

        // Seed actividades
        $this->call([
            ActividadSeeder::class,
        ]);
    }
}
