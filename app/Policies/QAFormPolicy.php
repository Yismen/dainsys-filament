<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\QAForm;
use Illuminate\Auth\Access\HandlesAuthorization;

class QAFormPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny qAForm');
    }

    public function view(AuthUser $authUser, QAForm $qAForm): bool
    {
        return $authUser->can('view qAForm');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create qAForm');
    }

    public function update(AuthUser $authUser, QAForm $qAForm): bool
    {
        return $authUser->can('update qAForm');
    }

    public function delete(AuthUser $authUser, QAForm $qAForm): bool
    {
        return $authUser->can('delete qAForm');
    }

    public function restore(AuthUser $authUser, QAForm $qAForm): bool
    {
        return $authUser->can('restore qAForm');
    }

    public function forceDelete(AuthUser $authUser, QAForm $qAForm): bool
    {
        return $authUser->can('forceDelete qAForm');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny qAForm');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny qAForm');
    }

    public function replicate(AuthUser $authUser, QAForm $qAForm): bool
    {
        return $authUser->can('replicate qAForm');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder qAForm');
    }

}