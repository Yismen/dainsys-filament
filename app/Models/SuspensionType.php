<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\HasManySuspensions;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'description'])]
class SuspensionType extends AppModel
{
    use HasManySuspensions;
    use SoftDeletes;

    public function employees(): HasManyThrough
    {
        return $this->hasManyThrough(
            Employee::class,
            Suspension::class,
            'suspension_type_id', // Foreign key on Suspensions table...
            'id', // Foreign key on Employees table...
            'id', // Local key on SuspensionTypes table...
            'employee_id' // Local key on Suspensions table...
        );
    }
}
