<?php

namespace App\Services;

use App\Models\Citizenship;
use Illuminate\Support\Facades\Cache;

class CitizenshipService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('citizenships_list', function () {
            return Citizenship::orderBy('name')->pluck('name', 'id');
        });
    }
}
