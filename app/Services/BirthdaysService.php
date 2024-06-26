<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Employee;
use InvalidArgumentException;
use Illuminate\Support\Collection;
use App\Services\Traits\HasFilters;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

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

    public function handle(string $type = 'today'): Collection
    {
        if (!in_array($type, $this->types)) {
            throw new InvalidArgumentException('Invalid argument passed. options are ' . join(', ', $this->types));
        }
        $this->type = $type;
        $this->date = now();

        $birthdays = $this->$type()->get();

        return $birthdays->map(function ($employee) {
            return [
                'id' => $employee->id,
                'name' => $employee->full_name,
                'date_of_birth' => $employee->date_of_birth->format('Y-m-d'),
                'age' => $employee->date_of_birth->age . ' years old',
                'site' => $employee->site->name,
                'project' => $employee->project->name,
                'position' => $employee->position->name,
                'department' => $employee->position->department->name,
                'supervisor' => optional($employee->supervisor)->name,
                'photo' => optional($employee->information)->photo_url,
            ];
        });
    }

    protected function today()
    {
        return $this->query
            ->whereMonth('date_of_birth', $this->date->month)->whereDay('date_of_birth', $this->date->day);
    }

    protected function yesterday()
    {
        return $this->query
            ->whereMonth('date_of_birth', $this->date->copy()->subDay()->month)->whereDay('date_of_birth', $this->date->copy()->subDay()->day);
    }

    protected function tomorrow()
    {
        return $this->query
            ->whereMonth('date_of_birth', $this->date->copy()->addDay()->month)->whereDay('date_of_birth', $this->date->copy()->addDay()->day);
    }

    protected function this_month()
    {
        return $this->query
            ->whereMonth(
                'date_of_birth',
                $this->date->month
            )->where(
                fn ($q) => $q
                    ->whereDay('date_of_birth', '>=', $this->date->copy()->startOfMonth())
                    ->orWhereDay('date_of_birth', '<=', $this->date->copy()->endOfMonth())
            );
    }

    protected function last_month()
    {
        return $this->query
            ->whereMonth(
                'date_of_birth',
                $this->date->copy()->subMonth()->month
            )->where(
                fn ($q) => $q
                    ->whereDay('date_of_birth', '>=', $this->date->copy()->subMonth()->startOfMonth()->day)
                    ->orWhereDay('date_of_birth', '<=', $this->date->copy()->subMonth()->endOfMonth()->day)
            );
    }

    protected function next_month()
    {
        return $this->query
            ->whereMonth(
                'date_of_birth',
                $this->date->copy()->addMonth()->month
            )->where(
                fn ($q) => $q
                    ->whereDay('date_of_birth', '>=', $this->date->copy()->addMonth()->startOfMonth()->day)
                    ->orWhereDay('date_of_birth', '<=', $this->date->copy()->addMonth()->endOfMonth()->day)
            );
    }

    protected function baseQuery(): Builder
    {
        $date = now();

        $this->query = Employee::query()
            ->with(['site', 'project', 'position.department', 'supervisor', 'information'])
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
