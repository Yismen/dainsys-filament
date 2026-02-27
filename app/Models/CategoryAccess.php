<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryAccess extends \App\Models\BaseModels\AppModel
{
    protected $fillable = [
        'category_id',
        'user_id',
        'role_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class)->withDefault();
    }
}
