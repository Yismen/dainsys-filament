<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\BelongsToAfp;
use App\Models\Traits\BelongsToArs;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'employee_id',
    'ars_id',
    'afp_id',
    'number',
])]
#[Table(name: 'social_securities')]
class SocialSecurity extends AppModel
{
    use BelongsToAfp;
    use BelongsToArs;
    use BelongsToEmployee;
    use SoftDeletes;
}
