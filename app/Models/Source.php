<?php

namespace App\Models;

use App\Models\Traits\HasManyCampaigns;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Source extends Model
{
    /** @use HasFactory<\Database\Factories\SourceFactory> */
    use HasFactory;

    use HasManyCampaigns;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = ['name', 'description'];
}
