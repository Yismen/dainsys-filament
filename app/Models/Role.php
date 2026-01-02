<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends \Spatie\Permission\Models\Role
{
    use HasUuids;
    use SoftDeletes;
}
