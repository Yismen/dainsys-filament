<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\HasHiredEmployees;
use App\Models\Traits\HasManyEmployees;
use App\Models\Traits\HasManyHires;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'person_of_contact', 'phone', 'email', 'address', 'geolocation', 'description'])]
class Site extends AppModel
{
    use HasHiredEmployees;
    use HasManyEmployees;
    use HasManyHires;
    use SoftDeletes;
}
