<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Information extends Model
{
    use HasFactory;
    protected $fillable = ['phone', 'email', 'photo_url', 'address', 'company_id', 'informationable_id', 'informationable_type'];

    protected $table = 'informations';
}
