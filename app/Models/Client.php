<?php

namespace App\Models;

use App\Models\Traits\HasInformation;
use App\Models\Traits\HasManyEmployees;
use App\Models\Traits\HasManyProjects;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasInformation;
    use HasManyProjects;
    use HasUuids;

    protected $fillable = ['name', 'person_of_contact', 'description'];

    public function campaigns(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Campaign::class, Project::class);
    }

    // public function productions(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    // {
    //     return $this->hasManyThrough(
    //         \App\Models\Production::class,
    //         \App\Models\Project::class,
    //         'client_id', // Foreign key on Hires table...
    //         'id', // Foreign key on Productions table...
    //         'id', // Local key on Citizenship table...
    //         'production_id' // Local key on Hires table...
    //     );
    // }


    // public function employees()
    // {
    //     return Employee::query()
    //         ->select('employees.*')
    //         ->join('hires', 'hires.employee_id', '=', 'employees.id')
    //         ->join('positions', 'positions.id', '=', 'hires.position_id')
    //         ->join('projects', 'projects.id', '=', 'positions.project_id')
    //         ->where('projects.client_id', $this->id)
    //         ->distinct()
    //         ->get();
    // }
}
