<?php

namespace App\Events;

use App\Models\Termination;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TerminationCreatedEvent
{
    use Dispatchable;
    use SerializesModels;

    public Termination $termination;

    public function __construct(Termination $termination)
    {
        $this->termination = $termination->load(['employee']);
    }
}
