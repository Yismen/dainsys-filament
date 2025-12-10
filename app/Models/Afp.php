<?php

namespace App\Models;

use App\Models\Traits\HasInformation;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Afp extends Model
{
    use HasFactory;
    use HasInformation;
    use SoftDeletes;
    use HasUuids;

    protected $fillable = ['name', 'person_of_contact', 'description'];

    public function socialSecurities(): HasMany
    {
        return $this->hasMany(SocialSecurity::class, 'afp_id');
    }

    public function employees(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            Employee::class,
            SocialSecurity::class,
            'afp_id', // Foreign key on SocialSecurities table...
            'id', // Foreign key on Employees table...
            'id', // Local key on Afps table...
            'employee_id' // Local key on SocialSecurities table...
        );
    }
}
