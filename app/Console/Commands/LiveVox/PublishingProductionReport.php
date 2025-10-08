<?php

namespace App\Console\Commands\LiveVox;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Services\HelpersService;
use App\Services\MailingService;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Mail\LiveVoxProductionReportMail;
use App\Exports\LiveVox\LivevoxProductionReport;

class PublishingProductionReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dainsys:livevox-publishing-production-report
    {--D|date= : string:Date or range of dates. Default is now()}
    {--S|subject= : string:Subject of the email to be sent}
    {--F|force : activate to send the report even if data is the same}
    ';

    protected $description = 'Send production report to subscribers';

    protected $file_name = 'livevox_publishing_production_report.xlsx';

    protected Carbon $date_from;

    protected Carbon $date_to;

    protected string $subject;

    public function handle()
    {
        $this->prepareCommand()
            ->createFile()
            ->emailRecipients();
    }

    protected function prepareCommand(): self
    {
        $dates = HelpersService::strToArray($this->option('date') ?: now()->format('Y-m-d'));
        $this->date_from = Carbon::parse($dates[0]);
        $this->date_to = Carbon::parse($dates[1] ?? $dates[0]);
        $this->subject = $this->option('subject')
            ? $this->option('subject') . ', From ' . $this->date_from->format('m-d-Y') . ' To ' . $this->date_to->format('m-d-Y')
            : 'Publishing Production Report';

        return $this;
    }

    protected function createFile(): self
    {
        Excel::store(
            new LivevoxProductionReport(
                service_name: 'PUB%',
                date_from: $this->date_from,
                date_to: $this->date_to,
            ),
            $this->file_name,
        );

        return $this;
    }

    protected function emailRecipients()
    {
        if ($this->reportDataChanged()) {
            $recipients = MailingService::subscribers(LiveVoxProductionReportMail::class);

            Mail::to($recipients)
                ->send(new LiveVoxProductionReportMail(
                    title: $this->subject,
                    attachment_files: [$this->file_name]
                ));

            Storage::delete($this->file_name);

            $this->line($this->subject . ' sent');
        }
    }

    protected function reportDataChanged(): bool
    {
        if ($this->option('force')) {
            return true;
        }

        $cache_key = str(join('-', [
            $this->subject,
            $this->file_name,
            $this->date_from,
            $this->date_to,
        ]))->kebab()->toString();

        $file_content = IOFactory::load(Storage::path($this->file_name));
        $hours = collect($file_content->getAllSheets()[0]->toArray())->skip(1)->sum(function ($record) {
            return $record[3];
        });

        if ($hours === cache()->get($cache_key)) {
            $this->warn('No updates for this report. Report not sent! You can upse the --force option');
            return false;
        }

        cache()->put($cache_key, $hours);

        return true;
    }
}
