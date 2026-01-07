<?php

namespace App\Models\Traits;

use App\Models\SocialSecurity;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasOneSocialSocialSecurity
{
    public function socialSecurity(): HasOne
    {
        return $this->hasOne(SocialSecurity::class);
    }
}
