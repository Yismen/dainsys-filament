<?php

namespace App\Traits\Models;

use Illuminate\Support\Facades\Cache;

trait InteractsWithModelCaching
{
    protected static function booted()
    {
        parent::booted();

        static::saved(function ($model) {
            Cache::flush();
        });

        static::deleted(function ($model) {
            Cache::flush();
        });
    }
}
