<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Client;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ClientPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny client');
    }

    public function view(AuthUser $authUser, Client $client): bool
    {
        return $authUser->can('view client');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create client');
    }

    public function update(AuthUser $authUser, Client $client): bool
    {
        return $authUser->can('update client');
    }

    public function delete(AuthUser $authUser, Client $client): bool
    {
        return $authUser->can('delete client');
    }

    public function restore(AuthUser $authUser, Client $client): bool
    {
        return $authUser->can('restore client');
    }

    public function forceDelete(AuthUser $authUser, Client $client): bool
    {
        return $authUser->can('forceDelete client');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny client');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny client');
    }

    public function replicate(AuthUser $authUser, Client $client): bool
    {
        return $authUser->can('replicate client');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder client');
    }
}
