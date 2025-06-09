<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user)
    {
        if ($user->can('view client')) {
            return true;
        }
        return $this->deny('You do not have permission to view client.');
    }
    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        if ($user->can('view client')) {
            return true;
        }
        return $this->deny('You do not have permission to view client.');
    }

    public function create(User $user)
    {
        if ($user->can('create client')) {
            return true;
        }
        return $this->deny('You do not have permission to create client.');
    }

    public function update(User $user)
    {
        if ($user->can('edit client')) {
            return true;
        }
        return $this->deny('You do not have permission to update client.');
    }

    public function delete(User $user)
    {
        if ($user->can('delete client')) {
            return true;
        }
        return $this->deny('You do not have permission to delete client.');
    }

}
