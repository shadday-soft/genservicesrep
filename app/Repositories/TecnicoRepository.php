<?php

namespace App\Repositories;

use App\Interfaces\TecnicoInterface;
use App\Mail\WelcomeUserMail;
use App\Models\Tecnico;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TecnicoRepository extends BaseRepository implements TecnicoInterface
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->makeModel();
    }

    public function model()
    {
        return Tecnico::class;
    }

    public function getAll($perPage = 15, $search = null)
    {
        $query = Tecnico::with('user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre_completo', 'like', "%{$search}%")
                    ->orWhere('identificacion', 'like', "%{$search}%")
                    ->orWhere('correo', 'like', "%{$search}%")
                    ->orWhere('eps', 'like', "%{$search}%");
            });
        }

        if ($perPage === null || $perPage === 'all') {
            return $query->get();
        }

        return $query->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Guardar la contraseña antes de hashearla para enviarla por correo
            $plainPassword = $data['identificacion'];

            // Crear el usuario primero
            $userData = [
                'name' => $data['nombre_completo'],
                'email' => $data['correo'],
                'password' => Hash::make($plainPassword), // Password por defecto es la identificación
                'role' => 'Tecnico',
            ];

            $user = $this->userRepository->create($userData);

            // Manejar la foto si existe
            if (isset($data['foto']) && $data['foto']) {
                $fotoPath = $data['foto']->store('tecnicos', 'public');
                $data['foto'] = $fotoPath;
            }

            // Añadir el user_id
            $data['user_id'] = $user->id;

            // Crear el técnico
            $tecnico = parent::create($data);

            // Enviar correo de bienvenida con credenciales
            Mail::to($data['correo'])->send(new WelcomeUserMail(
                userName: $data['nombre_completo'],
                userEmail: $data['correo'],
                userPassword: $plainPassword,
                userRole: 'Tecnico',
                loginUrl: url('/login')
            ));

            return $tecnico;
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $tecnico = $this->find($id);

            // Manejar la foto si existe y es un archivo nuevo
            if (isset($data['foto']) && $data['foto'] && is_object($data['foto'])) {
                // Eliminar foto anterior si existe
                if ($tecnico->foto) {
                    Storage::disk('public')->delete($tecnico->foto);
                }
                $fotoPath = $data['foto']->store('tecnicos', 'public');
                $data['foto'] = $fotoPath;
            } elseif (! isset($data['foto']) || $data['foto'] === null) {
                // Si no se envió foto o es null, mantener la actual
                unset($data['foto']);
            }

            // Actualizar el usuario si el correo cambió

            $this->userRepository->update($tecnico->user_id, [
                'email' => $data['correo'],
                'name' => $data['nombre_completo'],
                'password' => bcrypt($data['identificacion']),
            ]);

            // Actualizar el nombre si cambió

            return parent::update($id, $data);
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $tecnico = $this->find($id);

            // Eliminar foto si existe
            if ($tecnico->foto) {
                Storage::disk('public')->delete($tecnico->foto);
            }

            // Eliminar el técnico (el usuario se eliminará en cascada)
            return parent::delete($id);
        });
    }
}
