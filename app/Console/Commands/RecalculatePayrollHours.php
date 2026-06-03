<?php

namespace App\Console\Commands;

use App\Jobs\RefreshPayrollHoursJob;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;

#[Description('Recalculate payroll hours within a date range')]
#[Signature('dainsys:recalculate-payroll-hours {--from=} {--to=}')]
class RecalculatePayrollHours extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $from = $this->option('from') ?? now()->subDays(7)->toDateString();
        $to = $this->option('to') ?? now()->subDay()->toDateString();

        $fromDate = Carbon::parse($from)->startOfDay();
        $toDate = Carbon::parse($to)->startOfDay();

        if ($fromDate->greaterThan($toDate)) {
            $this->error('The from date must be before or equal to the to date.');

            return self::FAILURE;
        }

        $jobsByWeek = [];
        $currentDate = $fromDate->copy();

        while ($currentDate->lte($toDate)) {
            $weekKey = $currentDate->copy()->startOfWeek()->toDateString();

            $jobsByWeek[$weekKey] ??= new RefreshPayrollHoursJob($currentDate->toDateString());
            $currentDate->addDay();
        }

        Bus::batch(array_values($jobsByWeek))->dispatch();

        $this->info(sprintf(
            'Dispatched payroll hours recalculation by week for %s to %s',
            $fromDate->toDateString(),
            $toDate->toDateString()
        ));

        return self::SUCCESS;
    }
}
