<?php

namespace App\Filament\Supervisor\Widgets;

use App\Enums\EmployeeStatuses;
use App\Models\Employee;
use App\Services\BirthdaysService;
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

        $today = Carbon::now();
        $until = Carbon::now()->addDays(10);
        $service = new BirthdaysService;
        $employees = $service->between($today, $until);
        $ids = $employees->pluck('id')->all();

        $query = Employee::query()->whereIn('id', $ids)->where('supervisor_id', $supervisor->id);
        $driver = config('database.default');
        if ($driver === 'sqlite') {
            $monthExpr = "CAST(strftime('%m', date_of_birth) AS INTEGER)";
            $dayExpr = "CAST(strftime('%d', date_of_birth) AS INTEGER)";
        } else {
            $monthExpr = 'MONTH(date_of_birth)';
            $dayExpr = 'DAY(date_of_birth)';
        }

        return $query->orderByRaw("$monthExpr, $dayExpr asc");
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

    protected function getDateExtractionSyntax(string $driver): array
    {
        return match ($driver) {
            'mysql' => ['MONTH(date_of_birth)', 'DAY(date_of_birth)'],
            'pgsql' => ['EXTRACT(MONTH FROM date_of_birth)', 'EXTRACT(DAY FROM date_of_birth)'],
            'sqlite' => ['CAST(strftime("%m", date_of_birth) AS INTEGER)', 'CAST(strftime("%d", date_of_birth) AS INTEGER)'],
            'sqlsrv' => ['MONTH(date_of_birth)', 'DAY(date_of_birth)'],
            default => ['MONTH(date_of_birth)', 'DAY(date_of_birth)'],
        };
    }
}
