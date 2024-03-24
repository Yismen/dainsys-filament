<?php

namespace App\Models;

use App\Models\Traits\BelongsToBank;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankAccount extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToEmployee;
    use BelongsToBank;

    protected $fillable = ['employee_id', 'bank_id', 'account'];
}
