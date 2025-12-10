<?php

namespace App\Models;

use App\Models\Traits\BelongsToAfp;
use App\Models\Traits\BelongsToArs;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocialSecurity extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToAfp;
    use BelongsToEmployee;
    use BelongsToArs;
    use HasUuids;

    protected $table = 'social_securities';

    protected $fillable = [
        'employee_id',
        'ars_id',
        'afp_id',
        'number',
    ];
}
