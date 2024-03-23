<?php

namespace App\Models;

use App\Models\Traits\HasInformation;
use App\Models\Traits\HasManyCampaigns;
use App\Models\Traits\HasManyEmployees;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasManyEmployees;
    use HasManyCampaigns;
    use HasInformation;

    protected $fillable = ['name', 'description'];
}
