<?php

use App\Console\Commands\Birthdays;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\UpdateTicketStatus;
use App\Console\Commands\LiveVox\PublishingProductionReport;

Schedule::command(\App\Console\Commands\UpdatePendingSuspensions::class)->everyFifteenMinutes();
Schedule::command(\App\Console\Commands\UpdateEmployeeSuspensions::class)->dailyAt('03:00');
Schedule::command(\App\Console\Commands\SendEmployeesSuspendedEmail::class)->dailyAt('03:05');
Schedule::command(Birthdays::class, ['type' => 'today'])->dailyAt('04:00');
Schedule::command(Birthdays::class, ['type' => 'this_month'])->monthlyOn(1, '04:01');
// Publishing
Schedule::command(PublishingProductionReport::class, [
    '--date' => now()->format('Y-m-d'),
    '--subject' => 'Publishing Hourly Production Report',

])->hourly();

Schedule::command(UpdateTicketStatus::class)->everyThirtyMinutes();

Schedule::command('telescope:prune --hours=120')->daily();
