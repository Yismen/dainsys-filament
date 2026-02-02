<?php

use App\Console\Commands\RecalculatePayrollHours;
use App\Jobs\RefreshPayrollHoursJob;
use Illuminate\Bus\PendingBatch;
use Illuminate\Support\Facades\Bus;

it('dispatches a batch of refresh jobs for the provided date range', function () {
    Bus::fake();

    $this->artisan(RecalculatePayrollHours::class, [
        '--from' => '2026-01-01',
        '--to' => '2026-01-03',
    ])->assertExitCode(0);

    Bus::assertBatched(function (PendingBatch $batch) {
        $dates = collect($batch->jobs)
            ->filter(fn ($job) => $job instanceof RefreshPayrollHoursJob)
            ->map(fn (RefreshPayrollHoursJob $job) => $job->date)
            ->values()
            ->all();

        return $dates === ['2026-01-01', '2026-01-02', '2026-01-03'];
    });
});

it('does not dispatch a batch when the date range is invalid', function () {
    Bus::fake();

    $this->artisan(RecalculatePayrollHours::class, [
        '--from' => '2026-01-10',
        '--to' => '2026-01-01',
    ])->assertExitCode(1);

    Bus::assertNothingBatched();
});
