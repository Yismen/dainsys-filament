<?php

namespace App\Models;

use App\Events\SuspensionUpdated;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\BelongsToSuspensionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Suspension extends Model
{
    use HasFactory, SoftDeletes, BelongsToEmployee, BelongsToSuspensionType;

    protected $fillable = ['employee_id', 'suspension_type_id', 'starts_at', 'ends_at', 'comments'];

    protected $dispatchesEvents = [
        'saved' => SuspensionUpdated::class,
    ];

    public function getDurationAttribute()
    {
        return $this->starts_at ? $this->starts_at->diffInDays($this->ends_at) + 1 . ' days' : null;
    }

    public function scopeActive($query)
    {
        $query->where(function ($query) {
            $query
                ->whereDate('starts_at', '<=', now()->format('Y-m-d'))
                ->whereDate('ends_at', '>=', now()->format('Y-m-d'));
        });
    }

    public function getIsActiveAttribute(): bool
    {
        return  now()->isBetween($this->starts_at->startOfDay(), $this->ends_at->endOfDay());
    }
}
