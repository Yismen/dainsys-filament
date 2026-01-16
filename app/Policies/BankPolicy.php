<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Bank;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class BankPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny bank');
    }

    public function view(AuthUser $authUser, Bank $bank): bool
    {
        return $authUser->can('view bank');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create bank');
    }

    public function update(AuthUser $authUser, Bank $bank): bool
    {
        return $authUser->can('update bank');
    }

    public function delete(AuthUser $authUser, Bank $bank): bool
    {
        return $authUser->can('delete bank');
    }

    public function restore(AuthUser $authUser, Bank $bank): bool
    {
        return $authUser->can('restore bank');
    }

    public function forceDelete(AuthUser $authUser, Bank $bank): bool
    {
        return $authUser->can('forceDelete bank');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny bank');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny bank');
    }

    public function replicate(AuthUser $authUser, Bank $bank): bool
    {
        return $authUser->can('replicate bank');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder bank');
    }
}
