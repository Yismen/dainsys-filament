<?php

namespace App\Console\Commands;

use App\Jobs\RefreshPayrollHoursJob;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Refresh payroll hours from production records for a given date')]
#[Signature('dainsys:import-payroll-hours-from-production {date}')]
class ImportPayrollHoursFromProduction extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $date = $this->argument('date');
        RefreshPayrollHoursJob::dispatch($date);

        $this->info("Dispatched payroll hours refresh for {$date}");
    }
}
