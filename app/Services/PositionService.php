<?php

namespace App\Services;

use App\Models\Position;
use Illuminate\Support\Facades\Cache;

class PositionService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('positions_list', function () {
            return Position::orderBy('name')->pluck('name', 'id');
        });
    }
}
