<?php

namespace App\Models;

use App\Models\Traits\HasInformation;
use App\Models\Traits\HasManyEmployees;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Citizenship extends Model
{
    use HasFactory;
    use HasInformation;
    use HasManyEmployees;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = ['name', 'description'];
}
