<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\HasManyEmployees;
use App\Models\Traits\HasManyHires;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends AppModel
{
    use HasManyEmployees;
    use HasManyHires;
    use SoftDeletes;

    protected $fillable = ['name', 'person_of_contact', 'phone', 'email', 'address', 'geolocation', 'description'];
}
