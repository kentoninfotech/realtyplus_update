<?php

namespace App\Policies;

use App\Models\User;
use App\Models\accountheads;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountheadsPolicy
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
         if ($user->can('view accounthead')) {
            return true;
        }
        return $this->deny('You do not have permission to view finance records.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\accountheads  $accountheads
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, accountheads $accountheads)
    {
        if ($user->can('view accounthead')) {
            return true;
        }
        return $this->deny('You do not have permission to view finance records.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if ($user->can('create accounthead')) {
            return true;
        }
        return $this->deny('You do not have permission to create finance records.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\accountheads  $accountheads
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, accountheads $accountheads)
    {
        if ($user->can('edit accounthead')) {
            return true;
        }
        return $this->deny('You do not have permission to update finance records.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\accountheads  $accountheads
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, accountheads $accountheads)
    {
        if ($user->can('delete accounthead')) {
            return true;
        }
        return $this->deny('You do not have permission to delete finance records.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\accountheads  $accountheads
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, accountheads $accountheads)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\accountheads  $accountheads
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, accountheads $accountheads)
    {
        //
    }
}
