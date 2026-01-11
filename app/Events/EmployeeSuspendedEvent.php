<?php

namespace App\Events;

use App\Models\Suspension;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmployeeSuspendedEvent
{
    use Dispatchable;
    use SerializesModels;

    public Suspension $suspension;

    public function __construct(Suspension $suspension)
    {
        $this->suspension = $suspension->load([
            'employee' => fn ($q) => $q->with(['site', 'project', 'position']),
            'suspensionType',
        ]);
    }
}
