<?php

namespace App\Policies;

use App\Models\Supervisor;
use Illuminate\Foundation\Auth\User;

class SupervisorPolicy
{

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User                      $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User                      $user
     * @param  \App\Models\Supervisor                   $site
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Supervisor $site)
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User                      $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User                      $user
     * @param  \App\Models\Supervisor                   $site
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Supervisor $site)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User                      $user
     * @param  \App\Models\Supervisor                   $site
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Supervisor $site)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User                      $user
     * @param  \App\Models\Supervisor                   $site
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Supervisor $site)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User                      $user
     * @param  \App\Models\Supervisor                   $site
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Supervisor $site)
    {
        return false;
    }
}
