<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Traits\HasManyMailingSubscriptions;
use App\Traits\Models\InteractsWithModelCaching;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens;
    use HasFactory;
    use HasManyMailingSubscriptions;
    use HasRoles;
    use HasUuids;
    use InteractsWithModelCaching;
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
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        $panel_id = $panel->getId();

        if ($panel_id == 'admin') {
            return auth()->user()->isSuperAdmin();
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
}
