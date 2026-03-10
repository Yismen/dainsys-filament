<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PayrollHour;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class PayrollHourPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny payrollHour');
    }

    public function view(AuthUser $authUser, PayrollHour $payrollHour): bool
    {
        return $authUser->can('view payrollHour');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create payrollHour');
    }

    public function update(AuthUser $authUser, PayrollHour $payrollHour): bool
    {
        return $authUser->can('update payrollHour');
    }

    public function delete(AuthUser $authUser, PayrollHour $payrollHour): bool
    {
        return $authUser->can('delete payrollHour');
    }

    public function restore(AuthUser $authUser, PayrollHour $payrollHour): bool
    {
        return $authUser->can('restore payrollHour');
    }

    public function forceDelete(AuthUser $authUser, PayrollHour $payrollHour): bool
    {
        return $authUser->can('forceDelete payrollHour');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny payrollHour');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny payrollHour');
    }

    public function replicate(AuthUser $authUser, PayrollHour $payrollHour): bool
    {
        return $authUser->can('replicate payrollHour');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder payrollHour');
    }
}
