<?php

namespace App\Listeners;

use App\Events\EmployeeSaved;

class UpdateFullName
{
    public function handle(EmployeeSaved $event)
    {
        $event->employee->updateFullName();
    }
}
