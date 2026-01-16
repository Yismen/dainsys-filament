<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Source;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class SourcePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny source');
    }

    public function view(AuthUser $authUser, Source $source): bool
    {
        return $authUser->can('view source');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create source');
    }

    public function update(AuthUser $authUser, Source $source): bool
    {
        return $authUser->can('update source');
    }

    public function delete(AuthUser $authUser, Source $source): bool
    {
        return $authUser->can('delete source');
    }

    public function restore(AuthUser $authUser, Source $source): bool
    {
        return $authUser->can('restore source');
    }

    public function forceDelete(AuthUser $authUser, Source $source): bool
    {
        return $authUser->can('forceDelete source');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny source');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny source');
    }

    public function replicate(AuthUser $authUser, Source $source): bool
    {
        return $authUser->can('replicate source');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder source');
    }
}
