<?php

namespace App\Services;

use App\Models\Employee;
use App\Services\Traits\HasFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class BirthdaysService
{
    use HasFilters;

    protected string $type;

    protected Carbon $date;

    protected Builder $query;

    protected array $types = [
        'today',
        'yesterday',
        'tomorrow',
        'this_week',
        'next_week',
        'last_week',
        'this_month',
        'next_month',
        'last_month',
    ];

    public function __construct()
    {
        $this->query = $this->baseQuery();
    }

    /**
     * Get employees with birthdays between two dates (month/day only, year-agnostic, handles wrap).
     * $start and $end should be Carbon instances.
     */
    public function between(Carbon $start, Carbon $end): Collection
    {
        $query = $this->baseQuery();

        $startMonth = $start->month;
        $startDay = $start->day;
        $endMonth = $end->month;
        $endDay = $end->day;

        $driver = config('database.default');
        if ($driver === 'sqlite') {
            $monthExpr = "CAST(strftime('%m', date_of_birth) AS INTEGER)";
            $dayExpr = "CAST(strftime('%d', date_of_birth) AS INTEGER)";
        } else {
            $monthExpr = "MONTH(date_of_birth)";
            $dayExpr = "DAY(date_of_birth)";
        }

        if ($startMonth < $endMonth || ($startMonth === $endMonth && $startDay <= $endDay)) {
            $query->where(function ($q) use ($monthExpr, $dayExpr, $startMonth, $startDay, $endMonth, $endDay) {
                $q->whereRaw('('
                    . "($monthExpr > ? OR ($monthExpr = ? AND $dayExpr >= ?))"
                    . ' AND '
                    . "($monthExpr < ? OR ($monthExpr = ? AND $dayExpr <= ?))"
                    . ')', [
                        $startMonth, $startMonth, $startDay,
                        $endMonth, $endMonth, $endDay,
                    ]);
            });
        } else {
            // Range wraps over year boundary (e.g., Dec 28–Jan 5)
            $query->where(function ($q) use ($monthExpr, $dayExpr, $startMonth, $startDay, $endMonth, $endDay) {
                $q->where(function ($q) use ($monthExpr, $dayExpr, $startMonth, $startDay) {
                    $q->whereRaw("($monthExpr > ? OR ($monthExpr = ? AND $dayExpr >= ?))", [$startMonth, $startMonth, $startDay]);
                })->orWhere(function ($q) use ($monthExpr, $dayExpr, $endMonth, $endDay) {
                    $q->whereRaw("($monthExpr < ? OR ($monthExpr = ? AND $dayExpr <= ?))", [$endMonth, $endMonth, $endDay]);
                });
            });
        }

        return $query->get();
    }

    public function handle(string $type = 'today'): Collection
    {
        if (! in_array($type, $this->types)) {
            throw new InvalidArgumentException('Invalid argument passed. options are '.implode(', ', $this->types));
        }
        $this->type = $type;

        $birthdays = $this->$type()->get();

        return $birthdays->map(function ($employee) {
            return [
                'id' => $employee->id,
                'name' => $employee->full_name,
                'date_of_birth' => $employee->date_of_birth->format('Y-m-d'),
                'age' => $employee->date_of_birth->age.' years old',
                'site' => $employee->site?->name,
                'project' => $employee->project?->name,
                'position' => $employee->position?->name,
                'department' => $employee->position?->department->name,
                'supervisor' => $employee->supervisor?->name,
            ];
        });
    }

    protected function today()
    {
        return $this->query
            ->whereMonth('date_of_birth', now()->month)->whereDay('date_of_birth', now()->day);
    }

    protected function yesterday()
    {
        return $this->query
            ->whereMonth('date_of_birth', now()->subDay()->month)->whereDay('date_of_birth', now()->subDay()->day);
    }

    protected function tomorrow()
    {
        return $this->query
            ->whereMonth('date_of_birth', now()->addDay()->month)->whereDay('date_of_birth', now()->addDay()->day);
    }

    protected function this_month()
    {
        return $this->query
            ->whereMonth(
                'date_of_birth',
                now()->month
            )->where(
                fn ($q) => $q
                    ->whereDay('date_of_birth', '>=', now()->startOfMonth())
                    ->orWhereDay('date_of_birth', '<=', now()->endOfMonth())
            );
    }

    protected function last_month()
    {
        return $this->query
            ->whereMonth(
                'date_of_birth',
                now()->startOfMonth()->subMonth()->month
            )->where(
                fn ($q) => $q
                    ->whereDay('date_of_birth', '>=', now()->startOfMonth()->subMonth()->day)
                    ->WhereDay('date_of_birth', '<=', now()->endOfMonth()->subMonth()->day)
            );
    }

    protected function next_month()
    {
        return $this->query
            ->whereMonth(
                'date_of_birth',
                now()->startOfMonth()->addMonth()->month
            )->where(
                fn ($q) => $q
                    ->whereDay('date_of_birth', '>=', now()->startOfMonth()->addMonth()->day)
                    ->orWhereDay('date_of_birth', '<=', now()->endOfMonth()->addMonth()->day)
            );
    }

    protected function baseQuery(): Builder
    {
        $date = now();

        $this->query = Employee::query()
            ->with(['site', 'project', 'position.department', 'supervisor'])
            ->when(
                config('database.default') === 'sqlite',
                fn ($q) => $q->orderByRaw('strftime("%m%d"), date_of_birth'),
                fn ($q) => $q
                // ->orderByRaw('MONTH(date_of_birth)', 'ASC')
                // ->orderByRaw('DAY(date_of_birth)', 'ASC')
            )
            ->notInactive();

        return $this->query;
    }
}
