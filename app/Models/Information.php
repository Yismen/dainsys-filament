<?php

namespace App\Models;

use App\Models\Traits\BelongsToAfp;
use App\Models\Traits\BelongsToArs;
use App\Models\Traits\BelongsToBank;
use App\Models\Traits\BelongsToSite;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToEmployee;
use App\Models\Traits\BelongsToSupervisor;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Information extends Model
{
    use HasFactory, SoftDeletes, BelongsToSupervisor, BelongsToAfp, BelongsToArs, BelongsToBank, BelongsToSite, BelongsToEmployee;
    protected $fillable = ['phone', 'email', 'photo_url', 'address', 'company_id', 'informationable_id', 'informationable_type'];

    protected $table = 'informations';

    public function informationable(): MorphTo
    {
        return $this->morphTo();
    }
}
