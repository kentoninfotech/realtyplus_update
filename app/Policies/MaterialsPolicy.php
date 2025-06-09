<?php

namespace App\Policies;

use App\Models\User;
use App\Models\materials;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialsPolicy
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
        if ($user->can('view material')) {
            return true;
        }
        return $this->deny('You do not have permission to view materials.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\materials  $materials
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, materials $materials)
    {
        if ($user->can('view material')) {
            return true;
        }
        return $this->deny('You do not have permission to view this material.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if ($user->can('create material')) {
            return true;
        }
        return $this->deny('You do not have permission to create/update materials.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\materials  $materials
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, materials $materials)
    {
        if ($user->can('update material')) {
            return true;
        }
        return $this->deny('You do not have permission to update this material.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\materials  $materials
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, materials $materials)
    {
        if ($user->can('delete material')) {
            return true;
        }
        return $this->deny('You do not have permission to delete this material.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\materials  $materials
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, materials $materials)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\materials  $materials
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, materials $materials)
    {
        //
    }
}
