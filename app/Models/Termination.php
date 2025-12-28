<?php

namespace App\Models;

use App\Events\TerminationCreated;
use App\Models\Traits\BelongsToEmployee;
use App\Models\Traits\HasManyComments;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Termination extends \App\Models\BaseModels\AppModel
{
    use BelongsToEmployee;
    use HasManyComments;
    use SoftDeletes;

    protected $fillable = ['employee_id', 'date', 'termination_type', 'is_rehireable'];

    protected $dispatchesEvents = [
        'created' => TerminationCreated::class,
    ];

    protected $casts = [
        'date' => 'datetime:Y-m-d',
        'is_rehireable' => 'boolean',
    ];
}
