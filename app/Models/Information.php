<?php

namespace App\Models;

use App\Models\Traits\BelongsToAfp;
use App\Models\Traits\BelongsToArs;
use App\Models\Traits\BelongsToBank;
use App\Models\Traits\BelongsToEmployee;
use App\Models\Traits\BelongsToProject;
use App\Models\Traits\BelongsToSite;
use App\Models\Traits\BelongsToSupervisor;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Information extends Model
{
    use BelongsToAfp;
    use BelongsToArs;
    use BelongsToBank;
    use BelongsToEmployee;
    use BelongsToProject;
    use BelongsToSite;
    use BelongsToSupervisor;
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

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
