<?php

namespace App\Console\Commands;

use App\Jobs\RefreshPayrollHoursJob;
use Illuminate\Console\Command;

class ImportPayrollHoursFromProduction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dainsys:import-payroll-hours-from-production {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh payroll hours from production records for a given date';

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
