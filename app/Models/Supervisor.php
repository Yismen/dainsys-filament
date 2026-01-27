<?php

namespace App\Models;

use App\Models\Traits\HasManyHires;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supervisor extends \App\Models\BaseModels\AppModel
{
    use HasManyHires;
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'user_id', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
