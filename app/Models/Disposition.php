<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use Database\Factories\DispositionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Disposition extends AppModel
{
    /** @use HasFactory<DispositionFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'sales',
        'description',
    ];
}
