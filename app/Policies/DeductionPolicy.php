<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Deduction;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class DeductionPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny deduction');
    }

    public function view(AuthUser $authUser, Deduction $deduction): bool
    {
        return $authUser->can('view deduction');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create deduction');
    }

    public function update(AuthUser $authUser, Deduction $deduction): bool
    {
        return $authUser->can('update deduction');
    }

    public function delete(AuthUser $authUser, Deduction $deduction): bool
    {
        return $authUser->can('delete deduction');
    }

    public function restore(AuthUser $authUser, Deduction $deduction): bool
    {
        return $authUser->can('restore deduction');
    }

    public function forceDelete(AuthUser $authUser, Deduction $deduction): bool
    {
        return $authUser->can('forceDelete deduction');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny deduction');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny deduction');
    }

    public function replicate(AuthUser $authUser, Deduction $deduction): bool
    {
        return $authUser->can('replicate deduction');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder deduction');
    }
}
