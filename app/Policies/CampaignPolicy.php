<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Campaign;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class CampaignPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny campaign');
    }

    public function view(AuthUser $authUser, Campaign $campaign): bool
    {
        return $authUser->can('view campaign');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create campaign');
    }

    public function update(AuthUser $authUser, Campaign $campaign): bool
    {
        return $authUser->can('update campaign');
    }

    public function delete(AuthUser $authUser, Campaign $campaign): bool
    {
        return $authUser->can('delete campaign');
    }

    public function restore(AuthUser $authUser, Campaign $campaign): bool
    {
        return $authUser->can('restore campaign');
    }

    public function forceDelete(AuthUser $authUser, Campaign $campaign): bool
    {
        return $authUser->can('forceDelete campaign');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny campaign');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny campaign');
    }

    public function replicate(AuthUser $authUser, Campaign $campaign): bool
    {
        return $authUser->can('replicate campaign');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder campaign');
    }
}
