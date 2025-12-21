<?php

namespace App\Services;

use App\Models\Supervisor;
use Illuminate\Support\Facades\Cache;

class SupervisorService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('supervisors_list', function () {
            return Supervisor::orderBy('name')->pluck('name', 'id');
        });
    }
}
