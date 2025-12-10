<?php

namespace App\Models;

use App\Models\Traits\HasManyHires;
use App\Models\Traits\HasInformation;
use App\Models\Traits\BelongsToClient;
use App\Models\Traits\HasManyCampaigns;
use App\Models\Traits\HasManyEmployees;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToClient;
    use HasManyCampaigns;
    use HasManyHires;
    use HasInformation;
    use HasUuids;

    protected $fillable = ['name', 'client_id', 'description'];

    public function employees(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Employee::class,
            \App\Models\Hire::class,
            'project_id', // Foreign key on Hires table...
            'id', // Foreign key on Employees table...
            'id', // Local key on Projects table...
            'employee_id' // Local key on Hires table...
        );
    }
}
