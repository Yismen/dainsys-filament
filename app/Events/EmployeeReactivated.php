<?php

namespace App\Events;

use App\Models\Employee;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmployeeReactivated
{
    use Dispatchable;
    use SerializesModels;

    public Employee $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }
}
