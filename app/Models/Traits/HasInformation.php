<?php

namespace App\Models\Traits;

use App\Models\Information;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasInformation
{
    public function information(): MorphOne
    {
        return $this->morphOne(Information::class, 'informationable');
    }
}
