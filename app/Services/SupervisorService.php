<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Supervisor;

class SupervisorService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('supervisors_list', function () {
            return Supervisor::orderBy('name')->pluck('name', 'id');
        });
    }
}
