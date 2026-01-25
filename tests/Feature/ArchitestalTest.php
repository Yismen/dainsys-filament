<?php

/*
|
| Arhitectural tests
|
*/

use App\Models\Role;
use App\Models\User;
use App\Models\Mailable;
use App\Models\Permission;
use App\Models\MailableUser;
use App\Mail\TicketsExpiredMail;
use App\Models\BaseModels\AppModel;
use App\Mail\LiveVoxProductionReportMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Auth\User as AuthUser;

it('will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

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
        MailableUser::class,
        Mailable::class,
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

arch('mails should be queued')
    ->expect('App\Mail')
    ->toImplement(ShouldQueue::class)
    ->ignoring([
        LiveVoxProductionReportMail::class,
        TicketsExpiredMail::class,
    ]);
