<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Cache;

class ProjectService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('projects_list', function () {
            return Project::orderBy('name')->pluck('name', 'id');
        });
    }
}
