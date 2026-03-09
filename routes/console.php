<?php

use App\Console\Commands\Birthdays;
use App\Console\Commands\ImportPayrollHoursFromProduction;
use App\Console\Commands\LiveVox\PublishingProductionReport;
use App\Console\Commands\RecalculatePayrollHours;
use App\Console\Commands\SendTicketsExpiredReport;
use App\Console\Commands\UpdateTicketStatus;
use Illuminate\Support\Facades\Schedule;
use Spatie\Backup\Commands\BackupCommand;
use Spatie\Backup\Commands\CleanupCommand;

Schedule::command(\App\Console\Commands\UpdatePendingSuspensions::class)->everyFifteenMinutes();
Schedule::command(\App\Console\Commands\UpdateEmployeeSuspensions::class)->dailyAt('03:00');
Schedule::command(\App\Console\Commands\SendSuspendedEmployeesEmail::class)->dailyAt('03:05');
Schedule::command(Birthdays::class, ['today'])->dailyAt('04:00');
Schedule::command(Birthdays::class, ['this_month'])->monthlyOn(1, '04:01');

Schedule::command(ImportPayrollHoursFromProduction::class, [
    now()->subDay()->format('Y-m-d'),
])->hourlyAt(23);

Schedule::command(RecalculatePayrollHours::class)->dailyAt('02:00');

Schedule::command('telescope:prune --hours=120')->daily();

Schedule::command(UpdateTicketStatus::class)->everyThirtyMinutes();
Schedule::command(SendTicketsExpiredReport::class)->dailyAt('08:15');

Schedule::command(BackupCommand::class, ['--only-db'])->dailyAt('20:15');
Schedule::command(CleanupCommand::class)->dailyAt('21:15');

// LiveVox
// Schedule::command(PublishingProductionReport::class, [
//     '--date' => now()->format('Y-m-d'),
//     '--subject' => 'Publishing Hourly Production Report',

// ])->hourly();
