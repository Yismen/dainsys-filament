<?php

namespace App\Events;

use App\Models\Employee;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmployeeSaved
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Employee $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }
}
