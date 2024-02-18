<?php

namespace App\Models;

use App\Events\TerminationCreated;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToEmployee;
use App\Models\Traits\BelongsToTerminationType;
use App\Models\Traits\BelongsToTerminationReason;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Termination extends Model
{
    use HasFactory, BelongsToEmployee, BelongsToTerminationType, BelongsToTerminationReason;
    protected $fillable = ['employee_id', 'date', 'termination_type_id', 'termination_reason_id', 'comments', 'rehireable'];

    protected $dispatchesEvents = [
        'created' => TerminationCreated::class
    ];

    protected $casts = [
        'date' => 'date:Y-m-d'
    ];
}
