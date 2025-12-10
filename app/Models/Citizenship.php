<?php

namespace App\Models;

use App\Models\Traits\HasInformation;
use App\Models\Traits\HasManyEmployees;
use App\Models\Traits\HasManyHires;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Citizenship extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuids;
    use HasInformation;
    use HasManyEmployees;

    protected $fillable = ['name', 'description'];
}
