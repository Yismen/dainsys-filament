<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\HasManyCampaigns;
use Database\Factories\SourceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Source extends AppModel
{
    /** @use HasFactory<SourceFactory> */
    use HasFactory;

    use HasManyCampaigns;
    use SoftDeletes;

    protected $fillable = ['name', 'description'];
}
