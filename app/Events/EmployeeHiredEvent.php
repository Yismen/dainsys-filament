<?php

namespace App\Events;

use App\Models\Hire;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class EmployeeHiredEvent
{
    use Dispatchable;
    use SerializesModels;

    public Hire $hire;

    public function __construct(Hire $hire)
    {
        // $this->hire = $hire->load([
        //     'site',
        //     'project',
        // ]);
    }
}
