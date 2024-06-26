<?php

namespace App\Policies;

use App\Models\MailingSubscription;
use Illuminate\Foundation\Auth\User;

class MailingSubscriptionPolicy
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
     * @param  \App\Models\MailingSubscription           $site
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, MailingSubscription $site)
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
     * @param  \App\Models\MailingSubscription           $site
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, MailingSubscription $site)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User                      $user
     * @param  \App\Models\MailingSubscription           $site
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, MailingSubscription $site)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User                      $user
     * @param  \App\Models\MailingSubscription           $site
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, MailingSubscription $site)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User                      $user
     * @param  \App\Models\MailingSubscription           $site
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, MailingSubscription $site)
    {
        return false;
    }
}
