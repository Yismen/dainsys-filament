<?php

namespace App\Models;

use App\Models\Traits\BelongsToBank;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use BelongsToBank;
    use BelongsToEmployee;
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = ['employee_id', 'bank_id', 'account'];
}
