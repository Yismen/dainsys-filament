<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Disposition extends AppModel
{
    /** @use HasFactory<\Database\Factories\DispositionFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'sales',
        'description',
    ];
}
