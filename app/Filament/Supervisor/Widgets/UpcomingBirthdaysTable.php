<?php

namespace App\Filament\Supervisor\Widgets;

use App\Enums\EmployeeStatuses;
use App\Models\Employee;
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

        return Employee::query()
            ->active()
            ->where('supervisor_id', $supervisor->id)
            ->whereMonth('date_of_birth', $today->month)
            ->whereDay('date_of_birth', '>=', $today->day)
            ->whereDay('date_of_birth', '<=', $until->day)
            ->orderByRaw("DATE_FORMAT(date_of_birth, '%m-%d') asc");
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Employee')
                    ->searchable(),
                TextColumn::make('date_of_birth')
                    ->date()
                    ->label('Birthday')
                    ->formatStateUsing(fn (string $state) => Carbon::parse($state)->format('m-d').' ('.Carbon::parse($state)->age.' years)')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => EmployeeStatuses::Suspended,
                        'success' => EmployeeStatuses::Hired,
                    ]),
            ])
            ->emptyStateHeading('No upcoming birthdays')
            ->paginated(false);
    }
}
