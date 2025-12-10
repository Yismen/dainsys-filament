<?php

namespace App\Models;

use App\Models\Traits\HasInformation;
use App\Models\Traits\HasManyEmployees;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ars extends Model
{
    use HasFactory;
    use HasInformation;
    use SoftDeletes;
    use HasUuids;

    protected $fillable = ['name', 'person_of_contact', 'description'];

    protected $table = 'arss';

    public function socialSecurities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SocialSecurity::class, 'ars_id');
    }

    public function employees(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            Employee::class,
            SocialSecurity::class,
            'ars_id', // Foreign key on SocialSecurities table...
            'id', // Foreign key on Employees table...
            'id', // Local key on Arss table...
            'employee_id' // Local key on SocialSecurities table...
        );
    }
}
