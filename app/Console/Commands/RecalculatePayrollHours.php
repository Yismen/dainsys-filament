<?php

namespace App\Console\Commands;

use App\Jobs\RefreshPayrollHoursJob;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;

class RecalculatePayrollHours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dainsys:recalculate-payroll-hours {--from=} {--to=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate payroll hours within a date range';

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

        $jobs = [];
        $currentDate = $fromDate->copy();

        while ($currentDate->lte($toDate)) {
            $jobs[] = new RefreshPayrollHoursJob($currentDate->toDateString());
            $currentDate->addDay();
        }

        Bus::batch($jobs)->dispatch();

        $this->info(sprintf(
            'Dispatched payroll hours recalculation for %s to %s',
            $fromDate->toDateString(),
            $toDate->toDateString()
        ));

        return self::SUCCESS;
    }
}
