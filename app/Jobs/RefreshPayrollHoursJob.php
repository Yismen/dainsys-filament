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
use Illuminate\Support\Facades\Bus;
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

        $productions->each(function (Production $production): void {
            PayrollHour::updateOrCreate(
                [
                    'employee_id' => $production->employee_id,
                    'date' => Carbon::parse($production->date),
                ],
                [
                    'total_hours' => $production->sum_of_total_time,
                ]
            );
        });

        $employeeIds = $productions->pluck('employee_id')->unique()->values();

        if ($this->userToNotify) {
            Notification::make()
                ->title('Payroll hours have been updated!')
                ->body('Payroll hours for the week of '.$startOfWeek->toFormattedDateString().' - '.$endOfWeek->toFormattedDateString().' have been refreshed based on production records.')
                ->success()
                ->sendToDatabase($this->userToNotify);
        }

        if ($employeeIds->isEmpty()) {
            return;
        }

        Bus::batch(
            $employeeIds
                ->map(fn (string $employeeId) => new DistributePayrollHoursJob($this->date, $employeeId, $this->userToNotify))
                ->all()
        )->dispatch();
    }
}
