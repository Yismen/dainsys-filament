<?php

namespace App\Filament\HumanResource\Widgets;

use App\Enums\EmployeeStatuses;
use App\Filament\HumanResource\Resources\Employees\EmployeeResource;
use App\Models\Employee;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class UpcomingEmployeeBirthdays extends TableWidget
{
    protected static ?string $heading = 'Upcoming Employee Birthdays';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('full_name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable()
                    ->url(function (Employee $record) {
                        try {
                            return EmployeeResource::getUrl('view', ['record' => $record]);
                        } catch (\Exception $e) {
                            return null;
                        }
                    }),
                TextColumn::make('date_of_birth')
                    ->label('Birthday')
                    ->date('F j')
                    ->sortable(),
                TextColumn::make('age')
                    ->label('Turning Age')
                    ->state(function (Employee $record): int {
                        return now()->diffInYears($record->date_of_birth) + 1;
                    }),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('cellphone')
                    ->label('Phone'),
            ])
            ->defaultSort('date_of_birth', 'asc');
    }

    protected function getTableQuery(): Builder
    {
        $filters = $this->filters ?? [];
        $today = now();
        $endDate = now()->addDays(10);

        $query = Employee::query()
            ->where('status', '!=', EmployeeStatuses::Terminated)
            ->whereNotNull('date_of_birth')
            ->where(function (Builder $query) use ($today, $endDate): void {
                $startMonth = $today->month;
                $startDay = $today->day;
                $endMonth = $endDate->month;
                $endDay = $endDate->day;

                // Get database-specific month and day extraction syntax
                $driver = $query->getConnection()->getDriverName();
                [$monthExpr, $dayExpr] = $this->getDateExtractionSyntax($driver);

                if ($startMonth === $endMonth) {
                    // Same month
                    $query->whereRaw("{$monthExpr} = ?", [$startMonth])
                        ->whereRaw("{$dayExpr} BETWEEN ? AND ?", [$startDay, $endDay]);
                } else {
                    // Crosses month boundary
                    $query->where(function (Builder $q) use ($startMonth, $startDay, $endMonth, $endDay, $monthExpr, $dayExpr): void {
                        $q->where(function (Builder $q2) use ($startMonth, $startDay, $monthExpr, $dayExpr): void {
                            $q2->whereRaw("{$monthExpr} = ?", [$startMonth])
                                ->whereRaw("{$dayExpr} >= ?", [$startDay]);
                        })
                            ->orWhere(function (Builder $q2) use ($endMonth, $endDay, $monthExpr, $dayExpr): void {
                                $q2->whereRaw("{$monthExpr} = ?", [$endMonth])
                                    ->whereRaw("{$dayExpr} <= ?", [$endDay]);
                            });
                    });
                }
            });

        if (isset($filters['site']) && ! empty($filters['site'])) {
            $query->whereHas('hires', function ($q) use ($filters): void {
                $q->whereIn('site_id', $filters['site']);
            });
        }

        if (isset($filters['project']) && ! empty($filters['project'])) {
            $query->whereHas('hires', function ($q) use ($filters): void {
                $q->whereIn('project_id', $filters['project']);
            });
        }

        if (isset($filters['supervisor']) && ! empty($filters['supervisor'])) {
            $query->whereHas('hires', function ($q) use ($filters): void {
                $q->whereIn('supervisor_id', $filters['supervisor']);
            });
        }

        return $query;
    }

    /**
     * Get database-specific syntax for extracting month and day from date.
     */
    protected function getDateExtractionSyntax(string $driver): array
    {
        return match ($driver) {
            'mysql' => ['MONTH(date_of_birth)', 'DAY(date_of_birth)'],
            'pgsql' => ['EXTRACT(MONTH FROM date_of_birth)', 'EXTRACT(DAY FROM date_of_birth)'],
            'sqlite' => ['CAST(strftime("%m", date_of_birth) AS INTEGER)', 'CAST(strftime("%d", date_of_birth) AS INTEGER)'],
            'sqlsrv' => ['MONTH(date_of_birth)', 'DAY(date_of_birth)'],
            default => ['MONTH(date_of_birth)', 'DAY(date_of_birth)'], // Default to MySQL syntax
        };
    }
}
