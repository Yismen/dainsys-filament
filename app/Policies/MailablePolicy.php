<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Mailable;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class MailablePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny mailable');
    }

    public function view(AuthUser $authUser, Mailable $mailable): bool
    {
        return $authUser->can('view mailable');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create mailable');
    }

    public function update(AuthUser $authUser, Mailable $mailable): bool
    {
        return $authUser->can('update mailable');
    }

    public function delete(AuthUser $authUser, Mailable $mailable): bool
    {
        return $authUser->can('delete mailable');
    }

    public function restore(AuthUser $authUser, Mailable $mailable): bool
    {
        return $authUser->can('restore mailable');
    }

    public function forceDelete(AuthUser $authUser, Mailable $mailable): bool
    {
        return $authUser->can('forceDelete mailable');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny mailable');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny mailable');
    }

    public function replicate(AuthUser $authUser, Mailable $mailable): bool
    {
        return $authUser->can('replicate mailable');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder mailable');
    }
}
