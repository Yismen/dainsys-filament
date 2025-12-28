<?php

namespace App\Models;

use App\Models\Traits\BelongsToBank;
use App\Models\Traits\BelongsToEmployee;
use App\Traits\Models\InteractsWithModelCaching;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends \App\Models\BaseModels\AppModel
{
    use BelongsToBank;
    use BelongsToEmployee;
    use SoftDeletes;
    

    protected $fillable = ['employee_id', 'bank_id', 'account'];
}
