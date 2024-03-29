<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Performance;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateRevenueAndBillable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Carbon $date;

    /**
     * Create a new job instance.
     */
    public function __construct(Carbon $date)
    {
        $this->date = $date;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Performance::whereDate('created_at', $this->date)
            ->orWhereDate('updated_at', $this->date)
            ->get()
            ->each(function (Performance $model) {
                $model->update();
            });
    }
}
