<?php

use App\Console\Commands\RecalculatePayrollHours;
use App\Jobs\RefreshPayrollHoursJob;
use Illuminate\Bus\PendingBatch;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Bus;

it('dispatches one refresh job per unique week in the provided date range', function (): void {
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

        return $dates === ['2026-01-01'];
    });
});

it('dispatches multiple jobs when the date range spans multiple weeks', function (): void {
    Bus::fake();

    $this->artisan(RecalculatePayrollHours::class, [
        '--from' => '2026-01-03',
        '--to' => '2026-01-06',
    ])->assertExitCode(0);

    Bus::assertBatched(function (PendingBatch $batch) {
        $dates = collect($batch->jobs)
            ->filter(fn ($job) => $job instanceof RefreshPayrollHoursJob)
            ->map(fn (RefreshPayrollHoursJob $job) => $job->date)
            ->sort()
            ->values()
            ->all();

        return $dates === ['2026-01-03', '2026-01-05'];
    });
});

it('does not dispatch a batch when the date range is invalid', function (): void {
    Bus::fake();

    $this->artisan(RecalculatePayrollHours::class, [
        '--from' => '2026-01-10',
        '--to' => '2026-01-01',
    ])->assertExitCode(1);

    Bus::assertNothingBatched();
});

it('is is schedulled daily at 2:00 am', function (): void {

    $command = collect(app()->make(Schedule::class)->events())
        ->first(function ($element) {
            return str($element->command)->contains('dainsys:recalculate-payroll-hours');
        });

    expect($command)->not()->toBeNull();
    $this->assertEquals('8 */3 * * *', $command->expression);
});
