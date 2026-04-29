<?php

namespace App\Models;

use App\Enums\JobOpeningStatuses;
use App\Models\BaseModels\AppModel;
use App\Models\Traits\HasManyApplications;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'title',
    'description',
    'status',
    'position_id',
    'department_id',
    'site_id',
    'openings_count',
    'opened_at',
    'closed_at',
])]
class JobOpening extends AppModel
{
    use HasManyApplications;

    protected function casts(): array
    {
        return [
            'status' => JobOpeningStatuses::class,
            'opened_at' => 'date',
            'closed_at' => 'date',
        ];
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
