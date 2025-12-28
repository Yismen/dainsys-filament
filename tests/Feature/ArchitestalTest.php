<?php

/*
|
| Arhitectural tests
|
*/

use App\Models\Role;
use App\Models\Permission;
use App\Models\BaseModels\AppModel;
use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;

arch('all models should extend AppModel')
    ->expect('App\Models')
    ->toExtend(AppModel::class)
    ->ignoring([
        'App\Models\LiveVox',
        'App\Models\Services',
        'App\Models\Traits',
        'App\Models\BaseModels',
        Permission::class,
        Role::class,
        User::class,
    ]);

arch('AppModel should use traits HasFactory and InteractsWithModelCaching and HasUuids and SoftDeletes')
    ->expect(AppModel::class)
    ->toUseTraits([
        \Illuminate\Database\Eloquent\Factories\HasFactory::class,
        \App\Traits\Models\InteractsWithModelCaching::class,
        \Illuminate\Database\Eloquent\Concerns\HasUuids::class,
        \Illuminate\Database\Eloquent\SoftDeletes::class,
    ]);

arch('user model should not extend AppModel')
    ->expect(User::class)
    ->toExtend(AuthUser::class);

arch('expect user model to use traits HasFactory and InteractsWithModelCaching and HasUuids and HasRoles')
    ->expect(User::class)
    ->toUseTraits([
        \Illuminate\Database\Eloquent\Factories\HasFactory::class,
        \App\Traits\Models\InteractsWithModelCaching::class,
        \Illuminate\Database\Eloquent\Concerns\HasUuids::class,
        \Spatie\Permission\Traits\HasRoles::class,
    ]);