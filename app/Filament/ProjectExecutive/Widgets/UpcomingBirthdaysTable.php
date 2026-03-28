<?php

namespace App\Filament\ProjectExecutive\Widgets;

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
    protected static ?string $heading = 'Upcoming birthdays (next 10 days)';

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected function getTableQuery(): Builder
    {
        $managerId = Auth::id();

        if (! $managerId) {
            return Employee::query()->whereRaw('1 = 0');
        }

        $today = Carbon::now()->startOfDay();
        $until = Carbon::now()->addDays(10)->endOfDay();

        $query = Employee::query()
            ->active()
            ->whereHas('project', function (Builder $builder) use ($managerId): void {
                $builder->where('manager_id', $managerId);
            });

        if ($today->month === $until->month) {
            $query->whereMonth('date_of_birth', $today->month)
                ->whereDay('date_of_birth', '>=', $today->day)
                ->whereDay('date_of_birth', '<=', $until->day);
        } else {
            $query->where(function (Builder $builder) use ($today, $until): void {
                $builder->where(function (Builder $builder) use ($today): void {
                    $builder->whereMonth('date_of_birth', $today->month)
                        ->whereDay('date_of_birth', '>=', $today->day);
                })
                    ->orWhere(function (Builder $builder) use ($until): void {
                        $builder->whereMonth('date_of_birth', $until->month)
                            ->whereDay('date_of_birth', '<=', $until->day);
                    });
            });
        }

        [$monthExpr, $dayExpr] = $this->getDateExtractionSyntax(config('database.default'));

        return $query->orderByRaw("{$monthExpr}, {$dayExpr} asc");
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Employee')
                    ->searchable(),
                TextColumn::make('project.name')
                    ->label('Project')
                    ->sortable(),
                TextColumn::make('date_of_birth')
                    ->date()
                    ->label('Birthday')
                    ->formatStateUsing(fn (string $state): string => Carbon::parse($state)->format('m-d').' ('.Carbon::parse($state)->age.' years)')
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
