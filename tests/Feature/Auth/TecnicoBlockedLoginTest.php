<?php

namespace Tests\Feature\Auth;

use App\Models\Tecnico;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TecnicoBlockedLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_technician_can_login(): void
    {
        // Crear manualmente un técnico activo con credenciales conocidas
        $user = User::factory()->create([
            'name' => 'Juan Técnico',
            'email' => 'juan@tecnico.com',
            'role' => 'Tecnico',
            'password' => Hash::make('12345678'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);

        $tecnico = Tecnico::create([
            'user_id' => $user->id,
            'identificacion' => '12345678',
            'correo' => 'juan@tecnico.com',
            'nombre_completo' => 'Juan Técnico',
            'fecha_inicio_contrato' => now(),
            'tipo_contrato' => 'Indefinido',
            'activo' => true,
        ]);

        // Intentar iniciar sesión
        $response = $this->post(route('login.store'), [
            'email' => 'juan@tecnico.com',
            'password' => '12345678',
        ]);

        // Verificar que el login fue exitoso
        $this->assertAuthenticated();
    }

    public function test_inactive_technician_cannot_login(): void
    {
        // Crear manualmente un técnico inactivo
        $user = User::factory()->create([
            'name' => 'Pedro Técnico',
            'email' => 'pedro@tecnico.com',
            'role' => 'Tecnico',
            'password' => Hash::make('87654321'),
        ]);

        $tecnico = Tecnico::create([
            'user_id' => $user->id,
            'identificacion' => '87654321',
            'correo' => 'pedro@tecnico.com',
            'nombre_completo' => 'Pedro Técnico',
            'fecha_inicio_contrato' => now(),
            'tipo_contrato' => 'Indefinido',
            'activo' => false, // Técnico INACTIVO
        ]);

        // Intentar iniciar sesión
        $response = $this->post(route('login.store'), [
            'email' => 'pedro@tecnico.com',
            'password' => '87654321',
        ]);

        // Verificar que el login falló
        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_blocked_technician_cannot_login(): void
    {
        // Crear técnico activo y luego bloquearlo
        $user = User::factory()->create([
            'name' => 'Carlos Técnico',
            'email' => 'carlos@tecnico.com',
            'role' => 'Tecnico',
            'password' => Hash::make('11111111'),
        ]);

        $tecnico = Tecnico::create([
            'user_id' => $user->id,
            'identificacion' => '11111111',
            'correo' => 'carlos@tecnico.com',
            'nombre_completo' => 'Carlos Técnico',
            'fecha_inicio_contrato' => now(),
            'tipo_contrato' => 'Indefinido',
            'activo' => true,
        ]);

        // Bloquear al técnico
        $tecnico->update(['activo' => false]);

        // Intentar iniciar sesión
        $response = $this->post(route('login.store'), [
            'email' => 'carlos@tecnico.com',
            'password' => '11111111',
        ]);

        // Verificar que el login falló
        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_non_technician_users_can_still_login(): void
    {
        // Crear un usuario administrador (no técnico)
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'role' => 'Administrador',
            'password' => Hash::make('password'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);

        // Intentar iniciar sesión
        $response = $this->post(route('login.store'), [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        // Verificar que el login fue exitoso
        $this->assertAuthenticated();
    }
}
