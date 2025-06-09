<?php

namespace App\Policies;

use App\Models\User;
use App\Models\material_checkouts;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialCheckoutsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        if ($user->can('view material_checkout')) {
            return true;
        }
        return $this->deny('You do not have permission to view material checkouts.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\material_checkouts  $materialCheckouts
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, material_checkouts $materialCheckouts)
    {
        if ($user->can('view material_checkout')) {
            return true;
        }
        return $this->deny('You do not have permission to view this material checkout.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if ($user->can('create material_checkout')) {
            return true;
        }
        return $this->deny('You do not have permission to create/modify material checkouts.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\material_checkouts  $materialCheckouts
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, material_checkouts $materialCheckouts)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\material_checkouts  $materialCheckouts
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, material_checkouts $materialCheckouts)
    {
        if ($user->can('delete material_checkout')) {
            return true;
        }
        return $this->deny('You do not have permission to delete material checkouts.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\material_checkouts  $materialCheckouts
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, material_checkouts $materialCheckouts)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\material_checkouts  $materialCheckouts
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, material_checkouts $materialCheckouts)
    {
        //
    }
}
