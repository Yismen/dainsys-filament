<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegenerateIuidForModelJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $table)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function () {
            DB::table($this->table)
                ->lazyById(1000) // Fetches 1,000 at a time, but yields one-by-one
                ->each(function (object $record) {
                    // Perform your update
                    DB::table($this->table)
                        ->where('id', $record->id)
                        ->update([
                            'id' => (string) Str::uuid(),
                            'updated_at' => now(),
                        ]);
                });
            // DB::table($this->table)
            //     ->chunkById(1000, function($records) {
            //         foreach ($records as $record) {
            //             DB::update(
            //                 "UPDATE $this->table SET id = ? WHERE id = ?",
            //                 [(string) Str::uuid(), $record->id]
            //             );
            //         }
            //     });

        });
    }
}
