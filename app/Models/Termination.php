<?php

namespace App\Models;

use App\Events\TerminationCreated;
use App\Models\Traits\HasManyComments;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Termination extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToEmployee;
    use HasManyComments;
    use HasUuids;

    protected $fillable = ['employee_id', 'date', 'termination_type', 'is_rehireable'];

    protected $dispatchesEvents = [
        'created' => TerminationCreated::class
    ];

    protected $casts = [
        'date' => 'datetime:Y-m-d',
        'is_rehireable' => 'boolean',
    ];
}
