<?php

namespace App\Models;

use App\Models\Traits\BelongsToAfp;
use App\Models\Traits\BelongsToArs;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialSecurity extends \App\Models\BaseModels\AppModel
{
    use BelongsToAfp;
    use BelongsToArs;
    use BelongsToEmployee;
    use SoftDeletes;

    protected $table = 'social_securities';

    protected $fillable = [
        'employee_id',
        'ars_id',
        'afp_id',
        'number',
    ];
}
