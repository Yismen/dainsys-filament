<?php

namespace App\Models;

use App\Enums\AbsenceStatuses;
use App\Enums\AbsenceTypes;
use App\Models\BaseModels\AppModel;
use App\Models\Traits\BelongsToEmployee;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absence extends AppModel
{
    use BelongsToEmployee;

    protected $fillable = [
        'employee_id',
        'date',
        'status',
        'type',
        'comment',
        'created_by',
    ];

    protected static function booted(): void
    {
        static::creating(function (Absence $absence): void {
            if ($absence->employeeHasAbsenceOnDate($absence->employee_id, $absence->date)) {
                throw new \InvalidArgumentException('Employee already has an absence recorded for this date.');
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function employeeHasAbsenceOnDate(int|string $employeeId, string|CarbonInterface $date): bool
    {
        $date = Carbon::parse($date)->format('Y-m-d');

        return static::where('employee_id', $employeeId)
            ->whereDate('date', $date)
            ->exists();
    }

    #[Scope]
    protected function currentMonth($query)
    {
        $query->whereBetween('date', [
            now()->startOfMonth()->format('Y-m-d'),
            now()->endOfMonth()->format('Y-m-d'),
        ]);
    }

    #[Scope]
    protected function reported($query)
    {
        $query->where('status', AbsenceStatuses::Reported);
    }

    #[Scope]
    protected function withStatusCreated($query)
    {
        $query->where('status', AbsenceStatuses::Created);
    }

    public function isRedFlagged(): bool
    {
        return static::where('employee_id', $this->employee_id)
            ->currentMonth()
            ->count() >= 2;
    }

    public function scopeRedFlagged($query)
    {
        return $query->currentMonth()
            ->select('employee_id')
            ->groupBy('employee_id')
            ->havingRaw('COUNT(*) >= 2');
    }

    public function markAsReported(AbsenceTypes $type): void
    {
        $this->update([
            'status' => AbsenceStatuses::Reported,
            'type' => $type,
        ]);
    }

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
            'status' => AbsenceStatuses::class,
            'type' => AbsenceTypes::class,
        ];
    }
}
