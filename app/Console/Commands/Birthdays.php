<?php

namespace App\Console\Commands;

use App\Mail\BirthdaysMail;
use App\Notifications\Reports\BirthdaysReportNotification;
use App\Services\BirthdaysService;
use App\Services\MailingService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

#[Description('Sends a list of employees having birthdays in the given period')]
#[Signature('dainsys:birthdays
                            {type? : The type of report. Valid options are today, yesterday, tomorrow, this_month, next_month, last_month}')]
class Birthdays extends Command
{
    protected ?array $reportTypes = [
        'today' => 'Today',
        'yesterday' => 'Yesterday',
        'tomorrow' => 'Tomorrow',
        // 'this_week' => 'This Week',
        // 'last_week' => 'Last Week',
        // 'next_week' => 'Next Week',
        'this_month' => 'This Month',
        'last_month' => 'Last Month',
        'next_month' => 'Next Month',
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(BirthdaysService $birthdays)
    {
        $type = $this->argument('type');

        if (! array_key_exists($type, $this->reportTypes)) {
            $this->error('Invalid report. Valid options are '.implode(', ', array_keys($this->reportTypes)));

            return 1;
        }

        $birthdays = $birthdays->handle($type);

        if ($birthdays->count()) {
            $recipients = MailingService::subscribers(BirthdaysMail::class);
            Notification::send($recipients, new BirthdaysReportNotification($birthdays, str($type)->headline()));

            $this->info("Mail sent for {$birthdays->count()} employees having birthday {$type}");
        } else {
            $this->warn("No employees are having birthdays {$type}");
        }

        return 0;
    }
}
