<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\SuspensionType;

class SuspensionTypeService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('suspension_types_list', function () {
            return SuspensionType::orderBy('name')->pluck('name', 'id');
        });
    }
}
