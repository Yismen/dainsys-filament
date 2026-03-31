<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Evaluation;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class EvaluationPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny evaluation');
    }

    public function view(AuthUser $authUser, Evaluation $evaluation): bool
    {
        return $authUser->can('view evaluation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create evaluation');
    }

    public function update(AuthUser $authUser, Evaluation $evaluation): bool
    {
        return $authUser->can('update evaluation');
    }

    public function delete(AuthUser $authUser, Evaluation $evaluation): bool
    {
        return $authUser->can('delete evaluation');
    }

    public function restore(AuthUser $authUser, Evaluation $evaluation): bool
    {
        return $authUser->can('restore evaluation');
    }

    public function forceDelete(AuthUser $authUser, Evaluation $evaluation): bool
    {
        return $authUser->can('forceDelete evaluation');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny evaluation');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny evaluation');
    }

    public function replicate(AuthUser $authUser, Evaluation $evaluation): bool
    {
        return $authUser->can('replicate evaluation');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder evaluation');
    }
}
