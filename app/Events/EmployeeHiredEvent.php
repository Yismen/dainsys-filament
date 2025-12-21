<?php

namespace App\Events;

use App\Models\Hire;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmployeeHiredEvent
{
    use Dispatchable;
    use SerializesModels;

    public Hire $hire;

    public function __construct(Hire $hire)
    {
        $this->hire = $hire->load([
            'employee',
            'site',
            'project',
        ]);
    }
}
