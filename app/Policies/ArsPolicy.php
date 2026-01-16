<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Ars;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ArsPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny ars');
    }

    public function view(AuthUser $authUser, Ars $ars): bool
    {
        return $authUser->can('view ars');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create ars');
    }

    public function update(AuthUser $authUser, Ars $ars): bool
    {
        return $authUser->can('update ars');
    }

    public function delete(AuthUser $authUser, Ars $ars): bool
    {
        return $authUser->can('delete ars');
    }

    public function restore(AuthUser $authUser, Ars $ars): bool
    {
        return $authUser->can('restore ars');
    }

    public function forceDelete(AuthUser $authUser, Ars $ars): bool
    {
        return $authUser->can('forceDelete ars');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny ars');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny ars');
    }

    public function replicate(AuthUser $authUser, Ars $ars): bool
    {
        return $authUser->can('replicate ars');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder ars');
    }
}
