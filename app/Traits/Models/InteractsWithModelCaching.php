<?php

namespace App\Traits\Models;

use Illuminate\Support\Facades\Cache;

trait InteractsWithModelCaching
{
    protected static function booted()
    {
        parent::booted();

        static::saved(function ($model): void {
            Cache::flush();
        });

        static::deleted(function ($model): void {
            Cache::flush();
        });
    }
}
