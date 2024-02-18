<?php

namespace App\Listeners;

use App\Events\EmployeeSaved;

class UpdateEmployeeFullName
{
    public function handle(EmployeeSaved $event)
    {
        $event->employee->updateFullName();
    }
}
