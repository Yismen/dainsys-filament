<?php

namespace App\Models;

use App\Models\Traits\HasManySuspensions;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuspensionType extends Model
{
    use HasFactory;
    use HasManySuspensions;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

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
