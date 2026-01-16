<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Project;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny project');
    }

    public function view(AuthUser $authUser, Project $project): bool
    {
        return $authUser->can('view project');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create project');
    }

    public function update(AuthUser $authUser, Project $project): bool
    {
        return $authUser->can('update project');
    }

    public function delete(AuthUser $authUser, Project $project): bool
    {
        return $authUser->can('delete project');
    }

    public function restore(AuthUser $authUser, Project $project): bool
    {
        return $authUser->can('restore project');
    }

    public function forceDelete(AuthUser $authUser, Project $project): bool
    {
        return $authUser->can('forceDelete project');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny project');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny project');
    }

    public function replicate(AuthUser $authUser, Project $project): bool
    {
        return $authUser->can('replicate project');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder project');
    }
}
