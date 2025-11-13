<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();
        $tecnico = null;

        if ($user->role === 'Tecnico') {
            $tecnico = $user->tecnico()->first();
        }

        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
            'tecnico' => $tecnico,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Si el usuario es técnico, validar y actualizar información del técnico
        if ($user->role === 'Tecnico') {
            return $this->updateTecnicoProfile($request);
        }

        // Usuario regular - validar y actualizar solo nombre y email
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique(User::class)->ignore($user->id),
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return to_route('profile.edit');
    }

    /**
     * Update technician profile information.
     */
    protected function updateTecnicoProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        $tecnico = $user->tecnico;

        $validated = $request->validate([
            'nombre_completo' => ['required', 'string', 'max:255'],
            'identificacion' => ['required', 'string', 'max:255', 'min:8', \Illuminate\Validation\Rule::unique('tecnicos')->ignore($tecnico?->id)],
            'correo' => ['required', 'email', 'max:255', \Illuminate\Validation\Rule::unique('tecnicos')->ignore($tecnico?->id), \Illuminate\Validation\Rule::unique('users', 'email')->ignore($user->id)],
            'persona_contacto' => ['nullable', 'string', 'max:255'],
            'telefono_contacto' => ['nullable', 'string', 'max:20'],
            'direccion_contacto' => ['nullable', 'string', 'max:500'],
        ]);
        try {
            DB::beginTransaction();

            $tecnico = $user->tecnico;

            // Actualizar datos del técnico
            $tecnico->update([
                'nombre_completo' => $validated['nombre_completo'],
                'identificacion' => $validated['identificacion'],
                'correo' => $validated['correo'],
                'persona_contacto' => $validated['persona_contacto'] ?? null,
                'telefono_contacto' => $validated['telefono_contacto'] ?? null,
                'direccion_contacto' => $validated['direccion_contacto'] ?? null,
            ]);

            // Actualizar el usuario relacionado
            $emailChanged = $user->email !== $validated['correo'];

            $user->name = $validated['nombre_completo'];
            $user->email = $validated['correo'];

            if ($emailChanged) {
                $user->email_verified_at = null;
            }

            $user->save();

            DB::commit();

            return to_route('profile.edit')->with('status', 'Perfil actualizado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Error al actualizar el perfil: '.$e->getMessage()]);
        }
    }

    /**
     * Delete the user's profile.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
