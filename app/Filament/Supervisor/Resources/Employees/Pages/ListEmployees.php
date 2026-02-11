<?php

namespace App\Filament\Supervisor\Resources\Employees\Pages;

use App\Enums\EmployeeStatuses;
use App\Filament\Supervisor\Resources\EmployeeResource;
use App\Models\Employee;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getTableQuery(): Builder
    {
        $supervisor = Auth::user()?->supervisor;

        if (! $supervisor) {
            return Employee::query()->whereRaw('1 = 0');
        }

        return Employee::query()
            ->whereHas('supervisor', function ($query) use ($supervisor): void {
                $query->where('id', $supervisor->id);
            })
            ->whereNotIn('status', [EmployeeStatuses::Terminated]);
    }
}
