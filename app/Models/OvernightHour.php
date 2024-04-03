<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OvernightHour extends Model
{
    use HasFactory;
    use BelongsToEmployee;

    protected $fillable = ['date', 'employee_id', 'hours'];

    protected $casts = [
        'date' => 'date',
    ];
}
