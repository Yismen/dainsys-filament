<?php

namespace App\Models;

use App\Enums\EmployeeStatuses;
use App\Models\BaseModels\AppModel;
use App\Models\Scopes\IsActiveScope;
use App\Models\Traits\HasManyEmployees;
use App\Models\Traits\HasManyHires;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy([
    IsActiveScope::class,
])]
#[Fillable(['name', 'description', 'user_id', 'is_active'])]
class Supervisor extends AppModel
{
    use HasManyEmployees;
    use HasManyHires;
    use SoftDeletes;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hiredEmployees(): HasMany
    {
        return $this->employees()
            ->where('status', EmployeeStatuses::Hired)
            ->orderBy('full_name');
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
