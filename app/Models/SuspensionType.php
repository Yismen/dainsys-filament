<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasManySuspensions;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class SuspensionType extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasManySuspensions;
    use HasUuids;

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
