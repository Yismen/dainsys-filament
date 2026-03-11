<?php

/*
|
| Arhitectural tests
|
*/

use App\Mail\LiveVoxProductionReportMail;
use App\Mail\TicketsExpiredMail;
use App\Models\BaseModels\AppModel;
use App\Models\Mailable;
use App\Models\MailableUser;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Traits\Models\InteractsWithActivitylog;
use App\Traits\Models\InteractsWithModelCaching;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as AuthUser;
use Spatie\Permission\Traits\HasRoles;

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
        'App\Models\Scopes',
        Permission::class,
        Role::class,
        User::class,
        MailableUser::class,
        Mailable::class,
    ]);

arch('AppModel should use traits HasFactory and InteractsWithModelCaching and HasUuids and SoftDeletes')
    ->expect(AppModel::class)
    ->toUseTraits([
        HasFactory::class,
        InteractsWithModelCaching::class,
        InteractsWithActivitylog::class,
        HasUuids::class,
        SoftDeletes::class,
    ]);

arch('user model should not extend AppModel')
    ->expect(User::class)
    ->toExtend(AuthUser::class);

arch('expect user model to use traits HasFactory and InteractsWithModelCaching and HasUuids and HasRoles')
    ->expect(User::class)
    ->toUseTraits([
        HasFactory::class,
        InteractsWithModelCaching::class,
        HasUuids::class,
        HasRoles::class,
    ]);

arch('mails should be queued')
    ->expect('App\Mail')
    ->toImplement(ShouldQueue::class)
    ->ignoring([
        LiveVoxProductionReportMail::class,
        TicketsExpiredMail::class,
    ]);
