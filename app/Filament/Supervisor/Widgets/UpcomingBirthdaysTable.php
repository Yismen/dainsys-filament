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

    public static function canView(): bool
    {
        $user = Auth::user();
        $supervisor = $user?->supervisor;

        return $supervisor?->is_active === true;
    }

    protected function getTableQuery(): Builder
    {
        $supervisor = Auth::user()?->supervisor;

        if (! $supervisor) {
            return Employee::query()->whereRaw('1 = 0');
        }

        $today = Carbon::now()->startOfDay();
        $until = Carbon::now()->addDays(10)->endOfDay();

        $upcomingIds = Employee::query()
            ->whereHas('hires', function ($query) use ($supervisor) {
                $query->where('supervisor_id', $supervisor->id);
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
            ->whereKey($upcomingIds)
            ->orderByRaw('MONTH(date_of_birth), DAY(date_of_birth)');
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
                    ->label('Birthday'),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => EmployeeStatuses::Suspended,
                        'success' => EmployeeStatuses::Hired,
                    ]),
            ])
            ->emptyStateHeading('No upcoming birthdays')
            ->paginated(false);
    }
}
