<?php

namespace App\Models;

use App\Models\Traits\HasManyCampaigns;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Source extends \App\Models\BaseModels\AppModel
{
    /** @use HasFactory<\Database\Factories\SourceFactory> */
    use HasFactory;

    use HasManyCampaigns;
    use SoftDeletes;

    protected $fillable = ['name', 'description'];
}
