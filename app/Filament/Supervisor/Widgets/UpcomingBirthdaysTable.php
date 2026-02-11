<?php

namespace App\Filament\Supervisor\Widgets;

use App\Enums\EmployeeStatuses;
use App\Models\Employee;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class UpcomingBirthdaysTable extends BaseWidget
{
    protected static ?string $heading = 'Upcoming Birthdays (next 10 days)';

    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        $supervisor = Auth::user()?->supervisor;

        if (! $supervisor) {
            return Employee::query()->whereRaw('1 = 0');
        }

        $today = Carbon::now()->startOfDay();
        $until = Carbon::now()->addDays(10)->endOfDay();

        $upcomingIds = Employee::query()
            ->whereHas('supervisor', function ($query) use ($supervisor): void {
                $query->where('id', $supervisor->id);
            })
            ->where('status', EmployeeStatuses::Hired)
            ->whereNotNull('date_of_birth')
            ->get(['id', 'date_of_birth'])
            ->filter(function (Employee $employee) use ($today, $until) {
                $nextBirthday = Carbon::parse($employee->date_of_birth)->year($today->year);

                if ($nextBirthday->isBefore($today)) {
                    $nextBirthday->addYear();
                }

                return $nextBirthday->between($today, $until);
            })
            ->pluck('id')
            ->all();

        return Employee::query()
            ->whereKey($upcomingIds);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Employee')
                    ->searchable(),
                TextColumn::make('date_of_birth')
                    ->date('M d')
                    ->label('Birthday')
                    ->sortable(),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => EmployeeStatuses::Suspended,
                        'success' => EmployeeStatuses::Hired,
                    ]),
            ])
            ->defaultSort('date_of_birth', 'asc')
            ->emptyStateHeading('No upcoming birthdays')
            ->paginated(false);
    }
}
