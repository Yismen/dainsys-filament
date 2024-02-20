<?php

namespace App\Models;

use App\Models\Traits\HasManyPositions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentType extends Model
{
    use HasFactory, SoftDeletes, HasManyPositions;
    protected $fillable = ['name', 'description'];
}
