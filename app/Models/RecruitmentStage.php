<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\HasManyApplicationStageEvents;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'name',
    'description',
    'order',
])]
class RecruitmentStage extends AppModel
{
    use HasManyApplicationStageEvents;
}
