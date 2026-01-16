<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Termination;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class TerminationPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny termination');
    }

    public function view(AuthUser $authUser, Termination $termination): bool
    {
        return $authUser->can('view termination');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create termination');
    }

    public function update(AuthUser $authUser, Termination $termination): bool
    {
        return $authUser->can('update termination');
    }

    public function delete(AuthUser $authUser, Termination $termination): bool
    {
        return $authUser->can('delete termination');
    }

    public function restore(AuthUser $authUser, Termination $termination): bool
    {
        return $authUser->can('restore termination');
    }

    public function forceDelete(AuthUser $authUser, Termination $termination): bool
    {
        return $authUser->can('forceDelete termination');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny termination');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny termination');
    }

    public function replicate(AuthUser $authUser, Termination $termination): bool
    {
        return $authUser->can('replicate termination');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder termination');
    }
}
