<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BirthdaysService;
use Illuminate\Support\Facades\Mail;
use App\Mail\Birthdays as MailBirthdays;

class Birthdays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dainsys:birthdays
                            {type=today} The type of report. Options are today, yesterday, tomorrow, this_week, last_week, next_week, this_month, next_month, last_month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a list of employees having birthdays in the given period';

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
