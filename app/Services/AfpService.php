<?php

namespace App\Services;

use App\Models\Afp;
use Illuminate\Support\Facades\Cache;

class AfpService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('afps_list', function () {
            return Afp::orderBy('name')->pluck('name', 'id');
        });
    }
}
