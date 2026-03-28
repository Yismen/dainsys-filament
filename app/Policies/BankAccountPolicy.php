<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\BankAccount;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class BankAccountPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny bankAccount');
    }

    public function view(AuthUser $authUser, BankAccount $bankAccount): bool
    {
        return $authUser->can('view bankAccount');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create bankAccount');
    }

    public function update(AuthUser $authUser, BankAccount $bankAccount): bool
    {
        return $authUser->can('update bankAccount');
    }

    public function delete(AuthUser $authUser, BankAccount $bankAccount): bool
    {
        return $authUser->can('delete bankAccount');
    }

    public function restore(AuthUser $authUser, BankAccount $bankAccount): bool
    {
        return $authUser->can('restore bankAccount');
    }

    public function forceDelete(AuthUser $authUser, BankAccount $bankAccount): bool
    {
        return $authUser->can('forceDelete bankAccount');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny bankAccount');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny bankAccount');
    }

    public function replicate(AuthUser $authUser, BankAccount $bankAccount): bool
    {
        return $authUser->can('replicate bankAccount');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder bankAccount');
    }
}
