<?php

namespace App\Models\Traits;

use App\Models\Hire;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

trait HasRelationsThruHire
{
    public function site(): HasOneThrough
    {
        return $this->hasOneThrough(
            \App\Models\Site::class,
            Hire::class,
            'employee_id', // Foreign key on hires table...
            'id', // Foreign key on sites table...
            'id', // Local key on employees table...
            'site_id' // Local key on hires table...
        );
    }

    public function project(): HasOneThrough
    {
        return $this->hasOneThrough(
            \App\Models\Project::class,
            Hire::class,
            'employee_id', // Foreign key on hires table...
            'id', // Foreign key on projects table...
            'id', // Local key on employees table...
            'project_id' // Local key on hires table...
        );
    }

    public function position(): HasOneThrough
    {
        return $this->hasOneThrough(
            \App\Models\Position::class,
            Hire::class,
            'employee_id', // Foreign key on hires table...
            'id', // Foreign key on positions table...
            'id', // Local key on employees table...
            'position_id' // Local key on hires table...
        );
    }

    public function supervisor(): HasOneThrough
    {
        return $this->hasOneThrough(
            \App\Models\Supervisor::class,
            Hire::class,
            'employee_id', // Foreign key on hires table...
            'id', // Foreign key on supervisors table...
            'id', // Local key on employees table...
            'supervisor_id' // Local key on hires table...
        );
    }
}
