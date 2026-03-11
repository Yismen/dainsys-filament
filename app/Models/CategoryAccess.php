<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryAccess extends AppModel
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
