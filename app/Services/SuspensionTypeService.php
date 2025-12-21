<?php

namespace App\Services;

use App\Models\SuspensionType;
use Illuminate\Support\Facades\Cache;

class SuspensionTypeService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('suspension_types_list', function () {
            return SuspensionType::orderBy('name')->pluck('name', 'id');
        });
    }
}
