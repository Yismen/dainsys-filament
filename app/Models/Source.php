<?php

namespace App\Models;

use App\Models\Traits\HasManyCampaigns;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Source extends Model
{
    /** @use HasFactory<\Database\Factories\SourceFactory> */
    use HasFactory;
    use HasManyCampaigns;
    use SoftDeletes;
    use HasUuids;

    protected $fillable = ['name', 'description'];
}
