<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClientPolicy
{
<<<<<<< HEAD
     public function before(User $user, $ability)
    {
        if ($user->role === 'admin') {
            return true;
        }
    }
    
=======
>>>>>>> 200ba4eda14599c192446dd1af7ae94e055c543d
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Client $client): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Client $client): bool
    {
<<<<<<< HEAD
         if ($user->id === $client->id) {
            return true;
        }

=======
>>>>>>> 200ba4eda14599c192446dd1af7ae94e055c543d
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Client $client): bool
    {
<<<<<<< HEAD
         if ($user->id === $client->id) {
            return true;
        }

=======
>>>>>>> 200ba4eda14599c192446dd1af7ae94e055c543d
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Client $client): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Client $client): bool
    {
        return false;
    }
}
