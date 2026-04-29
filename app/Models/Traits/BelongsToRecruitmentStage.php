<?php

namespace App\Models\Traits;

use App\Models\RecruitmentStage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToRecruitmentStage
{
    public function recruitmentStage(): BelongsTo
    {
        return $this->belongsTo(RecruitmentStage::class);
    }
}
