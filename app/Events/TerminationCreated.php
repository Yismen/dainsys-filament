<?php

namespace App\Events;

use App\Models\Termination;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class TerminationCreated
{
    use Dispatchable;
    use SerializesModels;

    public Termination $termination;

    public function __construct(Termination $termination)
    {
        $this->termination = $termination->load(['employee']);
    }
}
