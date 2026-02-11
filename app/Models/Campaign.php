<?php

namespace App\Models;

use App\Enums\RevenueTypes;
use App\Models\Traits\BelongsToProject;
use App\Models\Traits\BelongsToSource;
use App\Models\Traits\HasManyProductions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends \App\Models\BaseModels\AppModel
{
    use BelongsToProject;
    use BelongsToSource;
    use HasManyProductions;
    use SoftDeletes;

    protected $fillable = ['name', 'project_id', 'source_id', 'revenue_type', 'sph_goal', 'revenue_rate', 'description'];

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function isDowntime(Builder $builder)
    {
        $builder->where('name', 'like', '%downtime%')
            ->orWhere('rate', '0');
    }

    protected function casts(): array
    {
        return [
            // 'source' => CampaignSources::class,
            'revenue_type' => RevenueTypes::class,
        ];
    }
}
