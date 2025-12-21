<?php

namespace App\Console\Commands;

use App\Mail\Birthdays as MailBirthdays;
use App\Services\BirthdaysService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Birthdays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dainsys:birthdays
                            {type? : The type of report. Valid options are today, yesterday, tomorrow, this_month, next_month, last_month}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a list of employees having birthdays in the given period';

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
            Mail::send(new MailBirthdays($birthdays, str($type)->headline()));

            $this->info("Mail sent for {$birthdays->count()} employees having birthday {$type}");
        } else {
            $this->warn("No employees are having birthdays {$type}");
        }

        return 0;
    }
}
