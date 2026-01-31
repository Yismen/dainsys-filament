<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Traits\BelongsToManyMailables;
use App\Models\Traits\BelongsToSupervisor;
use App\Models\Traits\HasOneSupervisor;
use App\Models\Traits\InteractWithSupportTickets;
use App\Traits\Models\InteractsWithModelCaching;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[ScopedBy([\App\Models\Scopes\IsActiveScope::class])]
class User extends Authenticatable implements FilamentUser
{
    use BelongsToManyMailables;
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use HasUuids;
    use HasOneSupervisor;
    use InteractsWithModelCaching;
    use InteractWithSupportTickets;
    use Notifiable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        if (! Auth::check()) {
            return true;
        }

        \abort_if(! Auth::user()->is_active, 403, 'You are an inactive user. Contact the administrator!');

        $panel_id = $panel->getId();

        if ($panel_id === 'admin') {
            return Auth::user()->isSuperAdmin();
        }

        if ($panel_id === 'human-resource') {
            return Auth::user()->can('manageHumanResources');
        }

        if ($panel_id === 'workforce') {
            return Auth::user()->can('manageWorkforce');
        }

        if ($panel_id === 'support') {
            return true;
        }

        if ($panel_id === 'supervisor') {
            return Gate::allows('manageSupervisor');
        }

        return true;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasAnyRole([
            'Super Admin',
            'super admin',
            'super-admin',
            'super_admin',
        ]);
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function employees(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            related: Employee::class,
            through: Supervisor::class,
        );
    }
}
