<?php

namespace App\Models\BaseModels;

use App\Traits\Models\InteractsWithActivitylog;
use App\Traits\Models\InteractsWithModelCaching;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppModel extends Model
{
    use HasFactory;
    use HasUuids;
    use InteractsWithModelCaching;
    use InteractsWithActivitylog;
    use SoftDeletes;
}
