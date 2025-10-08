<?php

use Mockery\MockInterface;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Models\LiveVox\LivevoxAgentSession;
use App\Models\Services\LivevoxAgentSessionService;
use App\Console\Commands\LiveVox\PublishingProductionReport;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Mail::fake();
    $orders_mock = \Mockery::mock(LivevoxAgentSessionService::class)->makePartial();
    $orders_mock->shouldReceive('query')->andReturn(\App\Models\User::query());
    $this->app->bind(LivevoxAgentSessionService::class, function () use ($orders_mock) {
        return $orders_mock;
    });
});

it('accepts parameters and options', function () {
    $command = $this->artisan('dainsys:livevox-publishing-production-report', [
        '--date' => '2024-03-12,2024-03-12',
        '--subject' => 'Publishing Hourly Report',
    ]);

    $command->assertSuccessful();
});


test('file is deleted after email is sent', function () {
    Storage::fake();
    Cache::flush();

    $this->artisan(PublishingProductionReport::class, [
        '--date' => '2024-03-12,2024-03-12',
        '--subject' => 'Publishing Hourly Report',
        // '--force' => true,
    ]);

    Storage::assertMissing('livevox_publishing_production_report.xlsx');
});
