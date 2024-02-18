<?php

namespace App\Listeners;

use App\Events\SuspensionUpdated;


class SuspendEmployee
{
    public function handle(SuspensionUpdated $event)
    {
        $employee = $event->suspension->employee;
        if ($employee->shouldBeSuspended()) {
            $employee->suspend();
        }
        if ($employee->shouldNotBeSuspended()) {
            $employee->unsuspend();
        }
    }
}
