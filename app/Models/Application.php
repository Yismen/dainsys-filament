<?php

namespace App\Models;

use App\Enums\ApplicationStatuses;
use App\Models\BaseModels\AppModel;
use App\Models\Traits\BelongsToApplicant;
use App\Models\Traits\BelongsToJobOpening;
use App\Models\Traits\HasManyApplicationStageEvents;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'applicant_id',
    'job_opening_id',
    'status',
    'notes',
    'applied_at',
])]
class Application extends AppModel
{
    use BelongsToApplicant;
    use BelongsToJobOpening;
    use HasManyApplicationStageEvents;

    protected function casts(): array
    {
        return [
            'status' => ApplicationStatuses::class,
            'applied_at' => 'date',
        ];
    }
}
