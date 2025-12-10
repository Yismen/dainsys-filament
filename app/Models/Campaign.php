<?php

namespace App\Models;

use App\Enums\RevenueTypes;
use App\Models\Traits\BelongsToSource;
use App\Models\Traits\BelongsToProject;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasManyProductions;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\BelongsToRevenueType;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToProject;
    use BelongsToSource;
    use HasManyProductions;
    use HasUuids;

    protected $fillable = ['name', 'project_id', 'source_id', 'revenue_type', 'sph_goal', 'revenue_rate', 'description'];

    protected $casts =  [
        // 'source' => CampaignSources::class,
        'revenue_type' => RevenueTypes::class,
    ];

    public function scopeIsDowntime(Builder $builder)
    {
        $builder->where('name', 'like', '%downtime%')
            ->orWhere('rate', '0');
    }
}
