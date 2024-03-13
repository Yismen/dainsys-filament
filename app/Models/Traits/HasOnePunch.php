<?php

namespace App\Models\Traits;

use App\Models\Punch;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasOnePunch
{
    public function punch(): HasOne
    {
        return $this->hasOne(Punch::class);
    }
}
