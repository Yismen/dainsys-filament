<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Payroll;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class PayrollPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('viewAny payroll');
    }

    public function view(AuthUser $authUser, Payroll $payroll): bool
    {
        return $authUser->can('view payroll');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create payroll');
    }

    public function update(AuthUser $authUser, Payroll $payroll): bool
    {
        return $authUser->can('update payroll');
    }

    public function delete(AuthUser $authUser, Payroll $payroll): bool
    {
        return $authUser->can('delete payroll');
    }

    public function restore(AuthUser $authUser, Payroll $payroll): bool
    {
        return $authUser->can('restore payroll');
    }

    public function forceDelete(AuthUser $authUser, Payroll $payroll): bool
    {
        return $authUser->can('forceDelete payroll');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('forceDeleteAny payroll');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restoreAny payroll');
    }

    public function replicate(AuthUser $authUser, Payroll $payroll): bool
    {
        return $authUser->can('replicate payroll');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder payroll');
    }
}
