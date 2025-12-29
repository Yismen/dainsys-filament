<?php

namespace App\Listeners;

use App\Enums\EmployeeStatuses;
use App\Events\TerminationCreated;

class TerminateEmployee
{
    public function handle(TerminationCreated $event)
    {
        $event->termination->employee->updateQuietly(['status' => EmployeeStatuses::Terminated]);
    }
}
