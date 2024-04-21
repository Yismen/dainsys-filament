<?php

namespace App\Models\LiveVox;

use App\Models\LiveVox\Contracts\LivevoxModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LivevoxAgentSession extends LivevoxModel
{
    use HasFactory;

    public function overrideTableName(): string
    {
        return 'fct_livevox_agent_sessions';
    }
}
