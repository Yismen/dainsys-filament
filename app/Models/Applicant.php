<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\HasManyApplications;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'name',
    'email',
    'phone',
    'resume_path',
    'notes',
])]
class Applicant extends AppModel
{
    use HasManyApplications;
}
