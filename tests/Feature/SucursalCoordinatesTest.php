<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SucursalCoordinatesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que una sucursal puede guardarse con coordenadas
     */
    public function test_sucursal_puede_guardarse_con_coordenadas(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);

        $sucursal = Sucursal::create([
            'client_id' => $client->id,
            'name' => 'Sucursal Test',
            'address' => 'Calle Test 123',
            'phone_number' => '1234567890',
            'contact_name' => 'Contacto Test',
            'email' => 'test@example.com',
            'latitude' => 4.7110,
            'longitude' => -74.0721,
        ]);

        $this->assertDatabaseHas('sucursals', [
            'id' => $sucursal->id,
            'name' => 'Sucursal Test',
            'latitude' => 4.7110,
            'longitude' => -74.0721,
        ]);
    }

    /**
     * Test que las coordenadas pueden ser null
     */
    public function test_coordenadas_pueden_ser_null(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);

        $sucursal = Sucursal::create([
            'client_id' => $client->id,
            'name' => 'Sucursal Sin Coordenadas',
            'address' => 'Calle Test 456',
            'phone_number' => '9876543210',
            'contact_name' => 'Otro Contacto',
            'email' => 'otro@example.com',
            'latitude' => null,
            'longitude' => null,
        ]);

        $this->assertDatabaseHas('sucursals', [
            'id' => $sucursal->id,
            'name' => 'Sucursal Sin Coordenadas',
        ]);

        $this->assertNull($sucursal->latitude);
        $this->assertNull($sucursal->longitude);
    }

    /**
     * Test que las coordenadas pueden actualizarse
     */
    public function test_coordenadas_pueden_actualizarse(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);

        $sucursal = Sucursal::create([
            'client_id' => $client->id,
            'name' => 'Sucursal Para Actualizar',
            'address' => 'Calle Test 789',
            'phone_number' => '5555555555',
            'contact_name' => 'Contacto Actualizable',
            'email' => 'actualizable@example.com',
            'latitude' => null,
            'longitude' => null,
        ]);

        $sucursal->update([
            'latitude' => 6.2476,
            'longitude' => -75.5658,
        ]);

        $this->assertDatabaseHas('sucursals', [
            'id' => $sucursal->id,
            'latitude' => 6.2476,
            'longitude' => -75.5658,
        ]);
    }
}
