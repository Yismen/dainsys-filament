<?php

namespace App\Models;

use App\Models\Traits\HasManyCampaigns;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RevenueType extends Model
{
    /** @use HasFactory<\Database\Factories\RevenueTypeFactory> */
    use HasFactory;
    use SoftDeletes;
    use HasManyCampaigns;
    use HasUuids;

    protected $fillable = ['name', 'description'];
}
