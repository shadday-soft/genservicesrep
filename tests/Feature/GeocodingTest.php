<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GeocodingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que la búsqueda de geocodificación requiere autenticación
     */
    public function test_geocoding_search_requires_authentication(): void
    {
        $response = $this->get('/geocoding/search?query=Bogota');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Test que la geocodificación inversa requiere autenticación
     */
    public function test_geocoding_reverse_requires_authentication(): void
    {
        $response = $this->get('/geocoding/reverse?lat=4.7110&lng=-74.0721');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Test que la búsqueda de geocodificación funciona correctamente
     */
    public function test_geocoding_search_works_correctly(): void
    {
        $user = User::factory()->create();

        Http::fake([
            'nominatim.openstreetmap.org/search*' => Http::response([
                [
                    'display_name' => 'Bogotá, Colombia',
                    'lat' => '4.7109886',
                    'lon' => '-74.072092',
                ],
            ], 200),
        ]);

        $response = $this->actingAs($user)->get('/geocoding/search?query=Bogota');

        $response->assertStatus(200);
        $response->assertJsonIsArray();
    }

    /**
     * Test que la geocodificación inversa funciona correctamente
     */
    public function test_geocoding_reverse_works_correctly(): void
    {
        $user = User::factory()->create();

        Http::fake([
            'nominatim.openstreetmap.org/reverse*' => Http::response([
                'address' => [
                    'city' => 'Bogotá',
                    'state' => 'Bogotá D.C.',
                    'country' => 'Colombia',
                ],
                'display_name' => 'Bogotá, Bogotá D.C., Colombia',
            ], 200),
        ]);

        $response = $this->actingAs($user)->get('/geocoding/reverse?lat=4.7110&lng=-74.0721');

        $response->assertStatus(200);
        $response->assertJsonStructure(['address', 'display_name']);
    }

    /**
     * Test que la búsqueda valida el parámetro query
     */
    public function test_geocoding_search_validates_query_parameter(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/geocoding/search');

        $response->assertStatus(302);
        $response->assertSessionHasErrors('query');
    }

    /**
     * Test que la geocodificación inversa valida los parámetros
     */
    public function test_geocoding_reverse_validates_coordinates(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/geocoding/reverse');

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['lat', 'lng']);
    }
}
