<?php

namespace App\Events;

use App\Models\HRActivityRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HRActivityRequestCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public HRActivityRequest $request) {}
}
