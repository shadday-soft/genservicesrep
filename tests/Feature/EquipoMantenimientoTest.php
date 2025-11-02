<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Equipo;
use App\Models\Solicitud;
use App\Models\Sucursal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipoMantenimientoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que al crear un equipo se generan solicitudes de mantenimiento automáticamente
     */
    public function test_crear_equipo_genera_solicitudes_mantenimiento_automaticamente(): void
    {
        // Arrange: Crear datos necesarios
        $this->seed(\Database\Seeders\ActividadSeeder::class);

        $client = Client::factory()->create();
        $sucursal = Sucursal::factory()->create([
            'client_id' => $client->id,
        ]);

        $fechasMantenimiento = [
            '2025-12-01',
            '2025-12-15',
            '2026-01-01',
        ];

        // Act: Crear equipo con fechas de mantenimiento
        $equipo = Equipo::create([
            'client_id' => $client->id,
            'sucursal_id' => $sucursal->id,
            'nombre_equipo' => 'Planta Test',
            'tipo_equipo' => 'Planta Eléctrica',
            'proximas_fechas_mantenimiento' => $fechasMantenimiento,
        ]);

        // Assert: Verificar que se crearon las solicitudes
        $this->assertDatabaseCount('solicituds', 3);

        foreach ($fechasMantenimiento as $fecha) {
            $this->assertDatabaseHas('solicituds', [
                'equipo_id' => $equipo->id,
                'client_id' => $client->id,
                'sucursal_id' => $sucursal->id,
                'fecha_programada' => $fecha,
                'fecha_mantenimiento' => $fecha,
                'actividad' => 'Mantenimiento preventivo',
                'estado' => 'Programada',
                'prioridad' => 'Normal',
                'user_id' => null,
            ]);
        }
    }

    /**
     * Test que al crear un equipo sin fechas de mantenimiento no se generan solicitudes
     */
    public function test_crear_equipo_sin_fechas_no_genera_solicitudes(): void
    {
        // Arrange
        $this->seed(\Database\Seeders\ActividadSeeder::class);

        $client = Client::factory()->create();
        $sucursal = Sucursal::factory()->create([
            'client_id' => $client->id,
        ]);

        // Act: Crear equipo sin fechas de mantenimiento
        Equipo::create([
            'client_id' => $client->id,
            'sucursal_id' => $sucursal->id,
            'nombre_equipo' => 'Planta Test',
            'tipo_equipo' => 'Planta Eléctrica',
        ]);

        // Assert: Verificar que no se crearon solicitudes
        $this->assertDatabaseCount('solicituds', 0);
    }

    /**
     * Test que las solicitudes creadas tienen user_id null
     */
    public function test_solicitudes_creadas_tienen_user_id_null(): void
    {
        // Arrange
        $this->seed(\Database\Seeders\ActividadSeeder::class);

        $client = Client::factory()->create();
        $sucursal = Sucursal::factory()->create([
            'client_id' => $client->id,
        ]);

        // Act
        $equipo = Equipo::create([
            'client_id' => $client->id,
            'sucursal_id' => $sucursal->id,
            'nombre_equipo' => 'Planta Test',
            'tipo_equipo' => 'Planta Eléctrica',
            'proximas_fechas_mantenimiento' => ['2025-12-01'],
        ]);

        // Assert
        $solicitud = Solicitud::where('equipo_id', $equipo->id)->first();
        $this->assertNull($solicitud->user_id);
    }
}
