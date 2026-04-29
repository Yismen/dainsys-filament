<?php

namespace App\Models;

use App\Enums\StageOutcome;
use App\Models\BaseModels\AppModel;
use App\Models\Traits\BelongsToApplication;
use App\Models\Traits\BelongsToRecruitmentStage;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'application_id',
    'recruitment_stage_id',
    'outcome',
    'scheduled_at',
    'completed_at',
    'notes',
])]
class ApplicationStageEvent extends AppModel
{
    use BelongsToApplication;
    use BelongsToRecruitmentStage;

    protected function casts(): array
    {
        return [
            'outcome' => StageOutcome::class,
            'scheduled_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }
}
