<?php

use App\Console\Commands\Birthdays;
use App\Console\Commands\ImportPayrollHoursFromProduction;
use App\Console\Commands\LiveVox\PublishingProductionReport;
use App\Console\Commands\RecalculatePayrollHours;
use App\Console\Commands\SendTicketsExpiredReport;
use App\Console\Commands\UpdateTicketStatus;
use Illuminate\Support\Facades\Schedule;

Schedule::command(\App\Console\Commands\UpdatePendingSuspensions::class)->everyFifteenMinutes();
Schedule::command(\App\Console\Commands\UpdateEmployeeSuspensions::class)->dailyAt('03:00');
Schedule::command(\App\Console\Commands\SendSuspendedEmployeesEmail::class)->dailyAt('03:05');
Schedule::command(Birthdays::class, ['type' => 'today'])->dailyAt('04:00');
Schedule::command(Birthdays::class, ['type' => 'this_month'])->monthlyOn(1, '04:01');
// Publishing
Schedule::command(PublishingProductionReport::class, [
    '--date' => now()->format('Y-m-d'),
    '--subject' => 'Publishing Hourly Production Report',

])->hourly();

Schedule::command(ImportPayrollHoursFromProduction::class, [
    'date' => now()->subDay()->format('Y-m-d'),
])->hourlyAt(23);

Schedule::command(RecalculatePayrollHours::class)->dailyAt('02:00');

Schedule::command('telescope:prune --hours=120')->daily();

Schedule::command(UpdateTicketStatus::class)->everyThirtyMinutes();
Schedule::command(SendTicketsExpiredReport::class)->dailyAt('08:15');
