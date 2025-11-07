<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tecnico>
 */
class TecnicoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tiposSangre = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $tiposContrato = ['Indefinido', 'Fijo', 'Obra o labor', 'Prestación de servicios'];
        $eps = ['Sanitas', 'Sura', 'Compensar', 'Famisanar', 'Nueva EPS', 'Salud Total'];

        $fechaInicio = fake()->dateTimeBetween('-2 years', 'now');
        $fechaFin = fake()->boolean(70) ? fake()->dateTimeBetween($fechaInicio, '+2 years') : null;

        return [
            'user_id' => User::factory()->state(['role' => 'Tecnico']),
            'foto' => fake()->boolean(50) ? 'tecnicos/'.fake()->uuid().'.jpg' : null,
            'identificacion' => fake()->unique()->numerify('##########'),
            'correo' => fake()->unique()->safeEmail(),
            'nombre_completo' => fake()->name(),
            'persona_contacto' => fake()->name(),
            'tipo_sangre' => fake()->randomElement($tiposSangre),
            'eps' => fake()->randomElement($eps),
            'fecha_nacimiento' => fake()->dateTimeBetween('-50 years', '-18 years'),
            'fecha_inicio_contrato' => $fechaInicio,
            'fecha_fin_contrato' => $fechaFin,
            'tipo_contrato' => fake()->randomElement($tiposContrato),
        ];
    }
}
