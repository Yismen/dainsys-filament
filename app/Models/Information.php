<?php

namespace App\Models;

use App\Models\Traits\BelongsToAfp;
use App\Models\Traits\BelongsToArs;
use App\Models\Traits\BelongsToBank;
use App\Models\Traits\BelongsToSite;
use App\Models\Traits\BelongsToProject;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToEmployee;
use App\Models\Traits\BelongsToSupervisor;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Information extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToSupervisor;
    use BelongsToAfp;
    use BelongsToArs;
    use BelongsToBank;
    use BelongsToSite;
    use BelongsToEmployee;
    use BelongsToProject;
    use HasUuids;

    protected $fillable = ['phone', 'email', 'photos', 'address', 'company_id', 'informationable_id', 'informationable_type'];

    protected $table = 'informations';

    protected $casts = [
        'photos' => 'array',
    ];

    public function informationable(): MorphTo
    {
        return $this->morphTo();
    }
}
