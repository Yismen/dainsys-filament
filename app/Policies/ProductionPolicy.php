<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Production;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ProductionPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny production');
    }

    public function view(AuthUser $authUser, Production $production): bool
    {
        return $authUser->can('view production');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create production');
    }

    public function update(AuthUser $authUser, Production $production): bool
    {
        return $authUser->can('update production');
    }

    public function delete(AuthUser $authUser, Production $production): bool
    {
        return $authUser->can('delete production');
    }

    public function restore(AuthUser $authUser, Production $production): bool
    {
        return $authUser->can('restore production');
    }

    public function forceDelete(AuthUser $authUser, Production $production): bool
    {
        return $authUser->can('forceDelete production');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny production');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny production');
    }

    public function replicate(AuthUser $authUser, Production $production): bool
    {
        return $authUser->can('replicate production');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder production');
    }
}
