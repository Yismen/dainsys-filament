<?php

namespace App\Models;

use App\Models\Traits\BelongsToClient;
use App\Models\Traits\HasManyCampaigns;
use App\Models\Traits\HasManyEmployees;
use App\Models\Traits\HasManyHires;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends \App\Models\BaseModels\AppModel
{
    use BelongsToClient;
    use HasManyCampaigns;
    use HasManyEmployees;
    use HasManyHires;
    use SoftDeletes;

    protected $fillable = ['name', 'client_id', 'description'];
}
