<?php

namespace App\Models;

use App\Models\Traits\BelongsToBank;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends \App\Models\BaseModels\AppModel
{
    use BelongsToBank;
    use BelongsToEmployee;
    use SoftDeletes;

    protected $fillable = ['employee_id', 'bank_id', 'account'];
}
