<?php

namespace Tests\Feature\Settings;

use App\Models\Tecnico;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TecnicoProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_technician_can_update_their_profile(): void
    {
        $user = User::factory()->create(['role' => 'Tecnico']);
        $tecnico = Tecnico::factory()->create([
            'user_id' => $user->id,
            'nombre_completo' => 'Juan Pérez',
            'identificacion' => '1234567890',
            'correo' => 'juan@example.com',
            'persona_contacto' => 'María Pérez',
            'telefono_contacto' => '3001234567',
            'direccion_contacto' => 'Calle 123 #45-67',
        ]);

        $response = $this->actingAs($user)
            ->from(route('profile.edit'))
            ->patch('/settings/profile', [
                'nombre_completo' => 'Juan Carlos Pérez',
                'identificacion' => '1234567890',
                'correo' => 'juancarlos@example.com',
                'persona_contacto' => 'Ana Pérez',
                'telefono_contacto' => '3009876543',
                'direccion_contacto' => 'Avenida 45 #12-34',
            ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('status', 'Perfil actualizado correctamente');

        $tecnico->refresh();
        $user->refresh();

        $this->assertEquals('Juan Carlos Pérez', $tecnico->nombre_completo);
        $this->assertEquals('juancarlos@example.com', $tecnico->correo);
        $this->assertEquals('Ana Pérez', $tecnico->persona_contacto);
        $this->assertEquals('3009876543', $tecnico->telefono_contacto);
        $this->assertEquals('Avenida 45 #12-34', $tecnico->direccion_contacto);

        $this->assertEquals('Juan Carlos Pérez', $user->name);
        $this->assertEquals('juancarlos@example.com', $user->email);
    }

    public function test_technician_email_verification_is_reset_when_email_changes(): void
    {
        $user = User::factory()->create([
            'role' => 'Tecnico',
            'email_verified_at' => now(),
        ]);

        $tecnico = Tecnico::factory()->create([
            'user_id' => $user->id,
            'correo' => $user->email,
        ]);

        $response = $this->actingAs($user)->patch('/settings/profile', [
            'nombre_completo' => $tecnico->nombre_completo,
            'identificacion' => $tecnico->identificacion,
            'correo' => 'newemail@example.com',
            'persona_contacto' => $tecnico->persona_contacto,
            'telefono_contacto' => $tecnico->telefono_contacto,
            'direccion_contacto' => $tecnico->direccion_contacto,
        ]);

        $user->refresh();

        $this->assertNull($user->email_verified_at);
    }

    public function test_technician_validation_requires_required_fields(): void
    {
        $user = User::factory()->create(['role' => 'Tecnico']);
        $tecnico = Tecnico::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->patch('/settings/profile', [
            'nombre_completo' => '',
            'identificacion' => '',
            'correo' => '',
        ]);

        $response->assertSessionHasErrors(['nombre_completo', 'identificacion', 'correo']);
    }

    public function test_technician_identification_must_be_unique(): void
    {
        $user1 = User::factory()->create(['role' => 'Tecnico']);
        $tecnico1 = Tecnico::factory()->create([
            'user_id' => $user1->id,
            'identificacion' => '1234567890',
        ]);

        $user2 = User::factory()->create(['role' => 'Tecnico']);
        $tecnico2 = Tecnico::factory()->create([
            'user_id' => $user2->id,
            'identificacion' => '0987654321',
        ]);

        $response = $this->actingAs($user2)->patch('/settings/profile', [
            'nombre_completo' => $tecnico2->nombre_completo,
            'identificacion' => '1234567890', // Trying to use tecnico1's ID
            'correo' => $tecnico2->correo,
        ]);

        $response->assertSessionHasErrors(['identificacion']);
    }

    public function test_technician_email_must_be_unique(): void
    {
        $user1 = User::factory()->create(['role' => 'Tecnico', 'email' => 'tech1@example.com']);
        $tecnico1 = Tecnico::factory()->create([
            'user_id' => $user1->id,
            'correo' => 'tech1@example.com',
        ]);

        $user2 = User::factory()->create(['role' => 'Tecnico', 'email' => 'tech2@example.com']);
        $tecnico2 = Tecnico::factory()->create([
            'user_id' => $user2->id,
            'correo' => 'tech2@example.com',
        ]);

        $response = $this->actingAs($user2)->patch('/settings/profile', [
            'nombre_completo' => $tecnico2->nombre_completo,
            'identificacion' => $tecnico2->identificacion,
            'correo' => 'tech1@example.com', // Trying to use tecnico1's email
        ]);

        $response->assertSessionHasErrors(['correo']);
    }

    public function test_regular_user_cannot_use_technician_update_endpoint(): void
    {
        $user = User::factory()->create(['role' => 'Cliente']);

        $response = $this->actingAs($user)->patch('/settings/profile', [
            'nombre_completo' => 'Test Name',
            'identificacion' => '1234567890',
            'correo' => 'test@example.com',
        ]);

        // El usuario regular debe poder actualizar con los campos regulares
        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function test_technician_profile_page_shows_technician_data(): void
    {
        $user = User::factory()->create(['role' => 'Tecnico']);
        $tecnico = Tecnico::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/settings/profile');

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('settings/Profile')
            ->has('tecnico')
            ->where('tecnico.id', $tecnico->id)
            ->where('tecnico.nombre_completo', $tecnico->nombre_completo)
            ->where('tecnico.correo', $tecnico->correo)
        );
    }
}
