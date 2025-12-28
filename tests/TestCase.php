<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\Permission;
use App\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createUserWithPermissionTo(string $permissionName): AuthUser
    {
        $user = User::factory()->create();

        Permission::firstOrCreate(['name' => $permissionName]);
        $user->givePermissionTo($permissionName);

        return $user;
    }

    public function createUserWithPermissionsToActions(array $actions, string $model_name): AuthUser
    {
        $user = User::factory()->create();

        foreach ($actions as $action) {
            $permissionName = $action.' '.$model_name;

            Permission::firstOrCreate(['name' => $permissionName]);
            $user->givePermissionTo($permissionName);
        }

        return $user;
    }

    public function createSuperAdminUser(): AuthUser
    {
        $user = User::factory()->create();
        $role = 'Super Admin';

        Role::create(['name' => $role]);
        $user->assignRole($role);

        return $user;
    }
}
