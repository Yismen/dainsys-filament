<?php

namespace App\Models;

use App\Enums\RevenueTypes;
use App\Models\Traits\BelongsToProject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\HasManyPerformances;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToProject;
    use HasManyPerformances;

    protected $fillable = ['name', 'project_id', 'source', 'revenue_type', 'goal', 'rate'];

    protected $casts =  [
        'revenue_type' => RevenueTypes::class
    ];

    public function scopeIsDowntime(Builder $builder)
    {
        $builder->where('name', 'like', '%downtime%')
            ->orWhere('rate', '0');
    }
}
