<?php

namespace App\Models;

use App\Models\Traits\HasInformation;
use App\Models\Traits\HasManyEmployees;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ars extends Model
{
    use HasFactory;
    use HasManyEmployees;
    use HasInformation;
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

    protected $table = 'arss';
}
