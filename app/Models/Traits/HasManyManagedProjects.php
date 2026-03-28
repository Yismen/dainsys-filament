<?php

namespace App\Models\Traits;

use App\Models\Project;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyManagedProjects
{
    public function managedProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'manager_id');
    }
}
