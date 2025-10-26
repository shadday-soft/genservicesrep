<?php

namespace Database\Seeders;

use App\Models\Actividad;
use Illuminate\Database\Seeder;

class ActividadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actividades = [
            'Mantenimiento preventivo',
            'Atención de emergencia',
            'Montaje de equipos',
            'Cambio de insumos',
            'Inspección',
            'Mantenimiento Transferencia',
            'Microfiltrado',
            'Soporte Técnico',
            'Mantenimiento Eléctrico',
            'Mantenimiento Sub-estación',
            'Mantenimiento Transformadores',
            'Mantenimiento Correctivo',
            'Cambio de Control',
            'Instalación de Tanque',
            'Correctivo Mecánico',
            'Mantenimiento Tuberías de Escape',
            'Lavados Generador-Radiador-Motor',
            'Cambio de Baterías',
            'Otro',
        ];

        foreach ($actividades as $actividad) {
            Actividad::create([
                'nombre' => $actividad,
                'active' => true,
            ]);
        }
    }
}
