<?php

namespace App\Models;

use App\Models\Traits\HasManyEmployees;
use App\Models\Traits\HasManyHires;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends \App\Models\BaseModels\AppModel
{
    use HasManyHires;
    use SoftDeletes;
    use HasManyEmployees;

    protected $fillable = ['name', 'person_of_contact', 'phone', 'email', 'address', 'geolocation', 'description'];
}
