<?php

test('command is schedulled for evey day at midnight', function (): void {
     $this->app->make(\Illuminate\Contracts\Console\Kernel::class);

    $addedToScheduler = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
        ->filter(function ($element) {
            return str($element->command)->contains('telescope:prune --hours=120');
        })->first();

    expect($addedToScheduler)->not->toBeNull();
    expect($addedToScheduler->expression)->toEqual('0 0 * * *');
});
