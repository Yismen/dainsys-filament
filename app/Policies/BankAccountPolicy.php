<?php

namespace App\Policies;

use App\Models\BankAccount;
use App\Models\User;

class BankAccountPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('viewAny bankAccount');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BankAccount $bankaccount): bool
    {
        return $user->checkPermissionTo('view bankAccount');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create bankAccount');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BankAccount $bankaccount): bool
    {
        return $user->checkPermissionTo('update bankAccount');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BankAccount $bankaccount): bool
    {
        return $user->checkPermissionTo('delete bankAccount');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any bankAccount');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BankAccount $bankaccount): bool
    {
        return $user->checkPermissionTo('restore bankAccount');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any bankAccount');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, BankAccount $bankaccount): bool
    {
        return $user->checkPermissionTo('replicate bankAccount');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder bankAccount');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BankAccount $bankaccount): bool
    {
        return $user->checkPermissionTo('force-delete bankAccount');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any bankAccount');
    }
}
