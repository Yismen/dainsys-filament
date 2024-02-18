<?php

namespace App\Events;

use App\Models\Suspension;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class SuspensionUpdated
{
    use Dispatchable;
    use SerializesModels;

    public Suspension $suspension;

    public function __construct(Suspension $suspension)
    {
        $this->suspension = $suspension->load([
            'employee' => fn ($q) => $q->with(['site', 'project', 'position']),
            'suspensionType'
        ]);
    }
}
