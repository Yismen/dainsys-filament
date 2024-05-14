<?php

namespace App\Console;

use App\Console\Commands\Birthdays;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\EmployeesSuspended;
use App\Console\Commands\UpdateEmployeeSuspensions;
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
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
