<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Department;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class DepartmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny department');
    }

    public function view(AuthUser $authUser, Department $department): bool
    {
        return $authUser->can('view department');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create department');
    }

    public function update(AuthUser $authUser, Department $department): bool
    {
        return $authUser->can('update department');
    }

    public function delete(AuthUser $authUser, Department $department): bool
    {
        return $authUser->can('delete department');
    }

    public function restore(AuthUser $authUser, Department $department): bool
    {
        return $authUser->can('restore department');
    }

    public function forceDelete(AuthUser $authUser, Department $department): bool
    {
        return $authUser->can('forceDelete department');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny department');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny department');
    }

    public function replicate(AuthUser $authUser, Department $department): bool
    {
        return $authUser->can('replicate department');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder department');
    }
}
