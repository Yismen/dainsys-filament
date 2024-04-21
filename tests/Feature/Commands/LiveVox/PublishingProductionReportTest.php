<?php

namespace Tests\Feature\Commands\LiveVox;

use Tests\TestCase;
use Mockery\MockInterface;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Models\LiveVox\LivevoxAgentSession;
use App\Models\Services\LivevoxAgentSessionService;
use App\Console\Commands\LiveVox\PublishingProductionReport;

class PublishingProductionReportTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        $orders_mock = \Mockery::mock(LivevoxAgentSessionService::class)->makePartial();
        $orders_mock->shouldReceive('query')->andReturn(\App\Models\User::query());
        $this->app->bind(LivevoxAgentSessionService::class, function () use ($orders_mock) {
            return $orders_mock;
        });
    }

    /** @test */
    public function it_accepts_parameters_and_options()
    {
        $command = $this->artisan('dainsys:livevox-publishing-production-report', [
            '--date' => '2024-03-12,2024-03-12',
            '--subject' => 'Publishing Hourly Report',
        ]);

        $command->assertSuccessful();
    }

    /** @test */
    // public function it_creates_an_excel_file()
    // {
    //     Excel::fake();

    //     $this->artisan(PublishingProductionReport::class, [
    //         '--date' => '2024-03-12,2024-03-12',
    //         '--subject' => 'Publishing Hourly Report',
    //     ]);

    //     Excel::assertStored('livevox_publishing_production_report.xlsx');
    // }

    /** @test */
    public function file_is_deleted()
    {
        Storage::fake();

        $this->artisan(PublishingProductionReport::class, [
            '--date' => '2024-03-12,2024-03-12',
            '--subject' => 'Publishing Hourly Report',
        ]);

        Storage::assertMissing('livevox_publishing_production_report.xlsx');
    }
}
