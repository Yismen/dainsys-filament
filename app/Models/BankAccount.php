<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\BelongsToBank;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['employee_id', 'bank_id', 'account'])]
class BankAccount extends AppModel
{
    use BelongsToBank;
    use BelongsToEmployee;
    use SoftDeletes;
}
