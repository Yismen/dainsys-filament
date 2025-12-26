<?php

namespace App\Console;

use App\Console\Commands\Birthdays;
use App\Console\Commands\EmployeesSuspended;
use App\Console\Commands\LiveVox\PublishingProductionReport;
use App\Console\Commands\UpdateEmployeeSuspensions;
use App\Console\Commands\UpdateTicketStatus;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command(UpdateEmployeeSuspensions::class)->dailyAt('03:00');
        $schedule->command(EmployeesSuspended::class)->dailyAt('03:05');
        $schedule->command(Birthdays::class, ['type' => 'today'])->dailyAt('04:00');
        $schedule->command(Birthdays::class, ['type' => 'this_month'])->monthlyOn(1, '04:01');
        $schedule->command(Birthdays::class, ['type' => 'last_month'])->monthlyOn(1, '04:05');
        $schedule->command(Birthdays::class, ['type' => 'next_month'])->monthlyOn(25, '04:10');
        // Publishing
        $schedule->command(PublishingProductionReport::class, [
            '--date' => now()->format('Y-m-d'),
            '--subject' => 'Publishing Hourly Production Report',

        ]);

        $schedule->command(UpdateTicketStatus::class)->everyThirtyMinutes();

        $schedule->command('telescope:prune --hours=48')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
