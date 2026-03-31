<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\QAQuestion;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class QAQuestionPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny qAQuestion');
    }

    public function view(AuthUser $authUser, QAQuestion $qAQuestion): bool
    {
        return $authUser->can('view qAQuestion');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create qAQuestion');
    }

    public function update(AuthUser $authUser, QAQuestion $qAQuestion): bool
    {
        return $authUser->can('update qAQuestion');
    }

    public function delete(AuthUser $authUser, QAQuestion $qAQuestion): bool
    {
        return $authUser->can('delete qAQuestion');
    }

    public function restore(AuthUser $authUser, QAQuestion $qAQuestion): bool
    {
        return $authUser->can('restore qAQuestion');
    }

    public function forceDelete(AuthUser $authUser, QAQuestion $qAQuestion): bool
    {
        return $authUser->can('forceDelete qAQuestion');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny qAQuestion');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny qAQuestion');
    }

    public function replicate(AuthUser $authUser, QAQuestion $qAQuestion): bool
    {
        return $authUser->can('replicate qAQuestion');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder qAQuestion');
    }
}
