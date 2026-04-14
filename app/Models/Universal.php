<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['employee_id', 'date_since'])]
class Universal extends AppModel
{
    use BelongsToEmployee;
    use SoftDeletes;

    protected static function booted()
    {
        parent::booted();

        static::creating(function (Universal $universal): void {
            if ($universal->date_since === null) {
                $universal->date_since = now();
            }
        });
    }
}
