<?php

namespace Tests;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createUserWithPermissionTo(string $permissionName): AuthUser
    {
        $user = User::factory()->create();
        $permissionName = \explode(' ', $permissionName, 2);
        $permissionName = str($permissionName[0])->camel().' '.str($permissionName[1])->camel();

        Permission::firstOrCreate(['name' => $permissionName]);
        $user->givePermissionTo($permissionName);

        return $user;
    }

    public function createUserWithPermissionsToActions(array $actions, string $model_name): AuthUser
    {
        $filamentPanel = Filament::getCurrentPanel()->getId() ?? null;

        if ($filamentPanel) {
            $roleName = str('manage-'.$filamentPanel);

            $role = Role::firstOrCreate(['name' => $roleName]);
        }
        $user = User::factory()->create();

        $user->assignRole($role);

        foreach ($actions as $action) {
            $permissionName = str($action)->camel().' '.str($model_name)->camel();

            Permission::firstOrCreate(['name' => $permissionName]);
            $user->givePermissionTo($permissionName);
        }

        return $user;
    }

    public function createSuperAdminUser(): AuthUser
    {
        $user = User::factory()->create();
        $role = 'Super Admin';

        Role::create(['name' => $role, 'guard_name' => 'web']);
        $user->assignRole($role);

        return $user;
    }
}
