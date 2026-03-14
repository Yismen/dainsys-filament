<?php

namespace App\Jobs;

use App\Models\PayrollHour;
use App\Models\Production;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RefreshPayrollHoursJob implements ShouldQueue
{
    use Batchable, Queueable;

    public function __construct(public string $date, public ?string $employeeId = null, public ?User $userToNotify = null) {}

    public function handle(): void
    {
        $date = Carbon::parse($this->date);
        $startOfWeek = $date->clone()->startOfWeek();
        $endOfWeek = $date->clone()->endOfWeek();

        $query = Production::query()
            ->whereDate('date', '>=', $startOfWeek)
            ->whereDate('date', '<=', $endOfWeek);

        // Filter by employee if provided
        if ($this->employeeId !== null) {
            $query->where('employee_id', $this->employeeId);
        }

        $productions = $query
            ->orderBy('date', 'asc')
            ->groupBy(['date', 'employee_id'])
            ->select([
                'date',
                'employee_id',
                DB::raw('sum(total_time) as sum_of_total_time'),
            ])
            ->get();

        if ($productions->isEmpty()) {
            return;
        }

        $holidayDates = DB::table('holidays')
            ->whereDate('date', '>=', $startOfWeek)
            ->whereDate('date', '<=', $endOfWeek)
            ->pluck('date')
            ->map(fn (string $holidayDate): string => Carbon::parse($holidayDate)->toDateString())
            ->flip();

        $now = now();

        $upsertRows = $productions
            ->map(function (Production $production) use ($holidayDates, $now): array {
                $workDate = Carbon::parse($production->date);
                $dateString = $workDate->toDateString();
                $totalHours = (float) $production->sum_of_total_time;
                $isHoliday = $holidayDates->has($dateString);

                return [
                    'employee_id' => $production->employee_id,
                    'date' => $dateString,
                    'total_hours' => $totalHours,
                    'regular_hours' => $isHoliday ? 0 : $totalHours,
                    'holiday_hours' => $isHoliday ? $totalHours : 0,
                    'seventh_day_hours' => 0,
                    'overtime_hours' => 0,
                    'is_holiday' => $isHoliday,
                    'is_sunday' => $workDate->isSunday(),
                    'week_ending_at' => $workDate->clone()->endOfWeek()->toDateString(),
                    'payroll_ending_at' => $workDate->day <= 15
                        ? $workDate->clone()->startOfMonth()->addDays(14)->toDateString()
                        : $workDate->clone()->endOfMonth()->toDateString(),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            });

        $upsertRows
            ->chunk(500)
            ->each(function (Collection $chunk): void {
                PayrollHour::query()->upsert(
                    $chunk->all(),
                    ['employee_id', 'date'],
                    [
                        'total_hours',
                        'regular_hours',
                        'holiday_hours',
                        'seventh_day_hours',
                        'overtime_hours',
                        'is_holiday',
                        'is_sunday',
                        'week_ending_at',
                        'payroll_ending_at',
                        'updated_at',
                    ]
                );
            });

        $employeeIds = $productions->pluck('employee_id')->unique()->values();

        $this->redistributeForWeek(
            $startOfWeek,
            $endOfWeek,
            $employeeIds
        );

        if ($this->userToNotify) {
            Notification::make()
                ->title('Payroll hours have been updated!')
                ->body('Payroll hours for the week of '.$startOfWeek->toFormattedDateString().' - '.$endOfWeek->toFormattedDateString().' have been refreshed and distributed based on production records.')
                ->success()
                ->sendToDatabase($this->userToNotify);
        }
    }

    protected function redistributeForWeek(Carbon $startOfWeek, Carbon $endOfWeek, Collection $employeeIds): void
    {
        if ($employeeIds->isEmpty()) {
            return;
        }

        $payrollHoursByEmployee = PayrollHour::query()
            ->whereDate('date', '>=', $startOfWeek)
            ->whereDate('date', '<=', $endOfWeek)
            ->whereIn('employee_id', $employeeIds->all())
            ->orderBy('date', 'asc')
            ->get()
            ->groupBy('employee_id');

        $updates = [];
        $now = now();

        foreach ($payrollHoursByEmployee as $employeePayrollHours) {
            $workedDays = $employeePayrollHours
                ->filter(fn (PayrollHour $payrollHour): bool => $payrollHour->total_hours > 0)
                ->count();

            $workedMoreThanSixDays = $workedDays > 6;

            $computed = [];

            foreach ($employeePayrollHours as $payrollHour) {
                $isHoliday = (bool) $payrollHour->is_holiday;
                $workDate = Carbon::parse($payrollHour->date);
                $isSunday = $workDate->isSunday();
                $totalHours = (float) $payrollHour->total_hours;

                $holidayHours = $isHoliday ? $totalHours : 0;
                $seventhDayHours = ! $isHoliday && $workedMoreThanSixDays && $isSunday ? $totalHours : 0;
                $regularHours = ! $isHoliday && $seventhDayHours == 0 ? $totalHours : 0;

                $computed[$payrollHour->id] = [
                    'id' => $payrollHour->id,
                    'employee_id' => $payrollHour->employee_id,
                    'date' => $workDate->toDateString(),
                    'total_hours' => (float) $payrollHour->total_hours,
                    'work_date' => $workDate,
                    'regular_hours' => $regularHours,
                    'overtime_hours' => 0,
                    'seventh_day_hours' => $seventhDayHours,
                    'holiday_hours' => $holidayHours,
                    'is_holiday' => $isHoliday,
                    'is_sunday' => $isSunday,
                    'week_ending_at' => $workDate->clone()->endOfWeek()->toDateString(),
                    'payroll_ending_at' => $workDate->day <= 15
                        ? $workDate->clone()->startOfMonth()->addDays(14)->toDateString()
                        : $workDate->clone()->endOfMonth()->toDateString(),
                ];
            }

            $totalRegularHours = collect($computed)->sum('regular_hours');
            $excessHours = $totalRegularHours - 44;

            if ($excessHours > 0) {
                foreach (collect($computed)->sortByDesc('work_date')->values() as $row) {
                    if ($excessHours <= 0) {
                        break;
                    }

                    if ($row['regular_hours'] <= 0) {
                        continue;
                    }

                    $hoursToShift = min($row['regular_hours'], $excessHours);

                    $computed[$row['id']]['regular_hours'] -= $hoursToShift;
                    $computed[$row['id']]['overtime_hours'] += $hoursToShift;

                    $excessHours -= $hoursToShift;
                }
            }

            foreach ($computed as $row) {
                $updates[] = [
                    'id' => $row['id'],
                    'employee_id' => $row['employee_id'],
                    'date' => $row['date'],
                    'total_hours' => $row['total_hours'],
                    'regular_hours' => $row['regular_hours'],
                    'overtime_hours' => $row['overtime_hours'],
                    'seventh_day_hours' => $row['seventh_day_hours'],
                    'holiday_hours' => $row['holiday_hours'],
                    'is_holiday' => $row['is_holiday'],
                    'is_sunday' => $row['is_sunday'],
                    'week_ending_at' => $row['week_ending_at'],
                    'payroll_ending_at' => $row['payroll_ending_at'],
                    'updated_at' => $now,
                ];
            }
        }

        collect($updates)
            ->chunk(500)
            ->each(function (Collection $chunk): void {
                PayrollHour::query()->upsert(
                    $chunk->all(),
                    ['id'],
                    ['regular_hours', 'overtime_hours', 'seventh_day_hours', 'holiday_hours', 'updated_at']
                );
            });
    }
}

