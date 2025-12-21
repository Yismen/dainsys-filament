<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Permission;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createUserWithPermissionTo(string $permission): AuthUser
    {
        $user = User::factory()->create();

        Permission::create(['name' => $permission]);
        $user->givePermissionTo($permission);

        return $user;
    }

    public function createUserWithPermissionsToActions(array $actions, string $model_name): AuthUser
    {
        $user = User::factory()->create();

        foreach ($actions as $action) {
            $permission = $action.' '.$model_name;

            Permission::create(['name' => $permission]);
            $user->givePermissionTo($permission);
        }

        return $user;
    }
}
