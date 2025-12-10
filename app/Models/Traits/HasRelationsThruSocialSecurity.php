<?php

namespace App\Models\Traits;

use App\Models\SocialSecurity;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

trait HasRelationsThruSocialSecurity
{
    public function afp(): HasOneThrough
    {
        return $this->hasOneThrough(
            \App\Models\Afp::class,
            SocialSecurity::class,
            'employee_id', // Foreign key on social security table...
            'id', // Foreign key on afps table...
            'id', // Local key on employees table...
            'afp_id' // Local key on social security table...
        );
    }

    public function ars(): HasOneThrough
    {
        return $this->hasOneThrough(
            \App\Models\Ars::class,
            SocialSecurity::class,
            'employee_id', // Foreign key on social security table...
            'id', // Foreign key on arss table...
            'id', // Local key on employees table...
            'ars_id' // Local key on social security table...
        );
    }
}
