<?php

namespace App\Models;

use App\Models\Traits\HasInformation;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ars extends Model
{
    use HasFactory;
    use HasInformation;
    use HasUuids;
    use SoftDeletes;

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
