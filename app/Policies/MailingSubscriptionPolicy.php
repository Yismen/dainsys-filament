<?php

namespace App\Policies;

use App\Models\MailingSubscription;
use App\Models\User;

class MailingSubscriptionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any MailingSubscription');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MailingSubscription $mailingsubscription): bool
    {
        return $user->checkPermissionTo('view MailingSubscription');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create MailingSubscription');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MailingSubscription $mailingsubscription): bool
    {
        return $user->checkPermissionTo('update MailingSubscription');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MailingSubscription $mailingsubscription): bool
    {
        return $user->checkPermissionTo('delete MailingSubscription');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any MailingSubscription');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MailingSubscription $mailingsubscription): bool
    {
        return $user->checkPermissionTo('restore MailingSubscription');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any MailingSubscription');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, MailingSubscription $mailingsubscription): bool
    {
        return $user->checkPermissionTo('replicate MailingSubscription');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder MailingSubscription');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MailingSubscription $mailingsubscription): bool
    {
        return $user->checkPermissionTo('force-delete MailingSubscription');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any MailingSubscription');
    }
}
