<?php

namespace Tests\Feature;

use App\Models\Equipo;
use App\Models\Informe;
use App\Models\Solicitud;
use App\Models\User;
use App\Models\Client;
use App\Models\Sucursal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InformeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /**
     * Test that pie_foto fields are saved correctly when storing an informe
     */
    public function test_pie_foto_fields_are_saved_when_storing_informe(): void
    {
        // Create necessary records
        $user = User::factory()->create(['role' => 'Tecnico']);
        $client = Client::factory()->create();
        $sucursal = Sucursal::factory()->create();
        $equipo = Equipo::factory()->create();

        $solicitud = Solicitud::factory()->create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'sucursal_id' => $sucursal->id,
            'equipo_id' => $equipo->id,
        ]);

        // Prepare form data with files and pie_foto fields
        $fotoUnoAntes = UploadedFile::fake()->image('foto_uno_antes.jpg', 640, 480);
        $fotoDosAntes = UploadedFile::fake()->image('foto_dos_antes.jpg', 640, 480);
        $fotoTresAntes = UploadedFile::fake()->image('foto_tres_antes.jpg', 640, 480);

        $fotoUnoData = [
            'solicitud_id' => $solicitud->id,
            'tipo_servicio' => 'Mantenimiento',
            'observaciones_iniciales' => 'Test observaciones',

            // Fotos antes with their captions
            'foto_uno_antes' => $fotoUnoAntes,
            'pie_foto_uno_antes' => 'Pie de foto uno antes',
            'foto_dos_antes' => $fotoDosAntes,
            'pie_foto_dos_antes' => 'Pie de foto dos antes',
            'foto_tres_antes' => $fotoTresAntes,
            'pie_foto_tres_antes' => 'Pie de foto tres antes',

            // Required fields
            'nombre_cliente' => 'Cliente Test',
        ];

        // Post the data
        $response = $this->actingAs($user)
            ->post(route('StoreInforme'), $fotoUnoData);

        // Verify the response
        $this->assertDatabaseHas('plantas_electricas', [
            'solicitud_id' => $solicitud->id,
            'tipo_servicio' => 'Mantenimiento',
            'pie_foto_uno_antes' => 'Pie de foto uno antes',
            'pie_foto_dos_antes' => 'Pie de foto dos antes',
            'pie_foto_tres_antes' => 'Pie de foto tres antes',
        ]);

        // Verify the informe record exists with the pie_foto data
        $informe = Informe::where('solicitud_id', $solicitud->id)->first();
        $this->assertNotNull($informe);
        $this->assertEquals('Pie de foto uno antes', $informe->pie_foto_uno_antes);
        $this->assertEquals('Pie de foto dos antes', $informe->pie_foto_dos_antes);
        $this->assertEquals('Pie de foto tres antes', $informe->pie_foto_tres_antes);
    }

    /**
     * Test that pie_foto fields are saved correctly when updating an informe
     */
    public function test_pie_foto_fields_are_saved_when_updating_informe(): void
    {
        // Create an existing informe
        $user = User::factory()->create(['role' => 'Tecnico']);
        $client = Client::factory()->create();
        $sucursal = Sucursal::factory()->create();
        $equipo = Equipo::factory()->create();

        $solicitud = Solicitud::factory()->create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'sucursal_id' => $sucursal->id,
            'equipo_id' => $equipo->id,
        ]);

        $informe = Informe::factory()->create([
            'solicitud_id' => $solicitud->id,
            'pie_foto_uno_antes' => 'Pie antiguo uno',
            'pie_foto_dos_antes' => 'Pie antiguo dos',
        ]);

        // Update with new pie_foto values
        $response = $this->actingAs($user)
            ->post(route('updateInforme', $informe->id), [
                'solicitud_id' => $solicitud->id,
                'tipo_servicio' => 'Mantenimiento',
                'pie_foto_uno_antes' => 'Pie nuevo uno',
                'pie_foto_dos_antes' => 'Pie nuevo dos',
                'pie_foto_tres_antes' => 'Pie nuevo tres',
                'nombre_cliente' => 'Cliente Test',
            ]);

        // Verify the pie_foto data was updated
        $this->assertDatabaseHas('plantas_electricas', [
            'id' => $informe->id,
            'pie_foto_uno_antes' => 'Pie nuevo uno',
            'pie_foto_dos_antes' => 'Pie nuevo dos',
            'pie_foto_tres_antes' => 'Pie nuevo tres',
        ]);

        // Refresh and verify
        $informe->refresh();
        $this->assertEquals('Pie nuevo uno', $informe->pie_foto_uno_antes);
        $this->assertEquals('Pie nuevo dos', $informe->pie_foto_dos_antes);
        $this->assertEquals('Pie nuevo tres', $informe->pie_foto_tres_antes);
    }

    /**
     * Test that all pie_foto fields (antes, durante, despues) are saved
     */
    public function test_all_pie_foto_sections_are_saved(): void
    {
        $user = User::factory()->create(['role' => 'Tecnico']);
        $client = Client::factory()->create();
        $sucursal = Sucursal::factory()->create();
        $equipo = Equipo::factory()->create();

        $solicitud = Solicitud::factory()->create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'sucursal_id' => $sucursal->id,
            'equipo_id' => $equipo->id,
        ]);

        // Create files
        $fotoAntes = UploadedFile::fake()->image('foto_antes.jpg');
        $fotoDurante = UploadedFile::fake()->image('foto_durante.jpg');
        $fotoDespues = UploadedFile::fake()->image('foto_despues.jpg');

        $data = [
            'solicitud_id' => $solicitud->id,
            'tipo_servicio' => 'Mantenimiento',

            // Fotos antes
            'foto_uno_antes' => $fotoAntes,
            'pie_foto_uno_antes' => 'Caption antes 1',
            'pie_foto_dos_antes' => 'Caption antes 2',

            // Fotos durante
            'foto_uno_durante' => $fotoDurante,
            'pie_foto_uno_durante' => 'Caption durante 1',
            'pie_foto_dos_durante' => 'Caption durante 2',
            'pie_foto_tres_durante' => 'Caption durante 3',

            // Fotos despues
            'foto_uno_despues' => $fotoDespues,
            'pie_foto_uno_despues' => 'Caption despues 1',
            'pie_foto_dos_despues' => 'Caption despues 2',

            'nombre_cliente' => 'Cliente Test',
        ];

        $this->actingAs($user)->post(route('StoreInforme'), $data);

        // Verify all pie_foto fields are saved
        $this->assertDatabaseHas('plantas_electricas', [
            'solicitud_id' => $solicitud->id,
            'pie_foto_uno_antes' => 'Caption antes 1',
            'pie_foto_dos_antes' => 'Caption antes 2',
            'pie_foto_uno_durante' => 'Caption durante 1',
            'pie_foto_dos_durante' => 'Caption durante 2',
            'pie_foto_tres_durante' => 'Caption durante 3',
            'pie_foto_uno_despues' => 'Caption despues 1',
            'pie_foto_dos_despues' => 'Caption despues 2',
        ]);
    }
}

