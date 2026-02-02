<?php

namespace App\Filament\Employee\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HoursTrendWidget extends ChartWidget
{
    protected ?string $heading = 'Hours Worked (Last 7 Days)';

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    public function getData(): array
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now();

        $payrollHours = Cache::remember(
            "employee_{$employee->id}_payroll_hours_{$startDate->toDateString()}_{$endDate->toDateString()}",
            now()->addHours(3),
            function () use ($employee, $startDate, $endDate) {
                return $employee->payrollHours()
                    ->whereBetween('date', [$startDate, $endDate])
                    ->orderBy('date')
                    ->get()
                    ->groupBy(fn ($record) => $record->date->format('M d'));
            }
        );

        $dates = [];
        $regularHours = [];
        $overtimeHours = [];
        $holidaysAndSeventhDay = [];

        foreach ($payrollHours as $date => $hours) {
            $dates[] = $date;
            $regularHours[] = $hours->sum('regular_hours');
            $overtimeHours[] = $hours->sum('overtime_hours');
            $holidaysAndSeventhDay[] = $hours->sum('holiday_hours') + $hours->sum('seventh_day_hours');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Regular Hours',
                    'data' => $regularHours,
                    'borderColor' => '#06b6d4',
                    'backgroundColor' => 'rgba(6, 182, 212, 0.1)',
                ],
                [
                    'label' => 'Overtime Hours',
                    'data' => $overtimeHours,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                ],
                [
                    'label' => 'Holidays & 7th Day Hours',
                    'data' => $holidaysAndSeventhDay,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
