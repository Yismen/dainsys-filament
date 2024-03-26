<?php

namespace App\Models;

use App\Models\Traits\BelongsToProject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToProject;

    protected $fillable = ['name', 'project_id', 'source', 'revenue_type', 'goal', 'rate'];

    public function scopeIsDowntime(Builder $builder)
    {
        $builder->where('name', 'like', '%downtime%')
            ->orWhere('rate', '0');
    }
}
