<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use Database\Factories\DispositionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable([
    'name',
    'sales',
    'description',
])]
class Disposition extends AppModel
{
    /** @use HasFactory<DispositionFactory> */
    use HasFactory;
}
