<?php

namespace App\Services;

use App\Models\Site;
use Illuminate\Support\Facades\Cache;

class SiteService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('sites_list', function () {
            return Site::orderBy('name')->pluck('name', 'id');
        });
    }
}
