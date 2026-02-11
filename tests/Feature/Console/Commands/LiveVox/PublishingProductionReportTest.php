<?php

use App\Console\Commands\LiveVox\PublishingProductionReport;
use App\Models\Services\LivevoxAgentSessionService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Mail::fake();
    $orders_mock = \Mockery::mock(LivevoxAgentSessionService::class)->makePartial();
    $orders_mock->shouldReceive('query')->andReturn(\App\Models\User::query());
    $this->app->bind(LivevoxAgentSessionService::class, function () use ($orders_mock) {
        return $orders_mock;
    });
});

it('accepts parameters and options', function (): void {
    $command = $this->artisan('dainsys:livevox-publishing-production-report', [
        '--date' => '2024-03-12,2024-03-12',
        '--subject' => 'Publishing Hourly Report',
    ]);

    $command->assertSuccessful();
});

test('file is deleted after email is sent', function (): void {
    Storage::fake();
    Cache::flush();

    $this->artisan(PublishingProductionReport::class, [
        '--date' => '2024-03-12,2024-03-12',
        '--subject' => 'Publishing Hourly Report',
        // '--force' => true,
    ]);

    Storage::assertMissing('livevox_publishing_production_report.xlsx');
});

it('runs hourly', function (): void {
    $addedToScheduler = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
        ->filter(function ($element) {
            return str($element->command)->contains('dainsys:livevox-publishing-production-report');
        })->first();

    expect($addedToScheduler)->not->toBeNull();
    expect($addedToScheduler->expression)->toEqual('0 * * * *');
});
