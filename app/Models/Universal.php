<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Universal extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToEmployee;

    protected $fillable = ['employee_id', 'date_since', 'comments'];
}
