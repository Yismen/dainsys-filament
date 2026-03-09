<?php

use Illuminate\Support\Facades\Mail;

beforeEach(function (): void {
    Mail::fake();
});

it('runs daily at 20:15', function (): void {

    $command = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
        ->first(function ($element) {
            return str($element->command)->contains('backup:run');
        });

    expect($command)->not()->toBeNull();
    expect($command->expression)->toEqual('15 20 * * *');
});

it('cleanup runs daily at 21:15', function (): void {

    $command = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
        ->first(function ($element) {
            return str($element->command)->contains('backup:clean');
        });

    expect($command)->not()->toBeNull();
    expect($command->expression)->toEqual('15 21 * * *');
});
