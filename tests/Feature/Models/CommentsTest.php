<?php

use App\Models\Afp;
use App\Models\Ars;
use App\Models\Bank;
use App\Models\Site;
use App\Models\Employee;
use App\Models\Supervisor;
use App\Models\Comment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

test('information model interacts with db table', function () {
    $data = Comment::factory()->create();

    $this->assertDatabaseHas('comments', $data->only([
        'text', 'commentable_id', 'commentable_type'
    ]));
});

test('information model uses soft delete', function () {
    $information = Comment::factory()->create();

    $information->delete();

    $this->assertSoftDeleted(Comment::class, [
        'id' => $information->id
    ]);
});

test('information model morph hire', function () {
    $hire = \App\Models\Hire::factory()->create();

    $this->assertDatabaseHas('comments', $hire->toArray());
    expect($hire->comments)->toBeInstanceOf(Comment::class);
    expect($hire->comments())->toBeInstanceOf(MorphMany::class);
});

test('information model morph suspension', function () {
    $suspension = \App\Models\Suspension::factory()->create();

    $this->assertDatabaseHas('comments', $suspension->toArray());
    expect($suspension->comments)->toBeInstanceOf(Comment::class);
    expect($suspension->comments())->toBeInstanceOf(MorphMany::class);
});

test('information model morph downtime', function () {
    $downtime = \App\Models\Downtime::factory()->create();

    $this->assertDatabaseHas('comments', $downtime->toArray());
    expect($downtime->comments)->toBeInstanceOf(Comment::class);
    expect($downtime->comments())->toBeInstanceOf(MorphMany::class);
});

test('information model morph production', function () {
    $production = \App\Models\Production::factory()->create();

    $this->assertDatabaseHas('comments', $production->toArray());
    expect($production->comments)->toBeInstanceOf(Comment::class);
    expect($production->comments())->toBeInstanceOf(MorphMany::class);
});

test('information model morph hour', function () {
    $hour = \App\Models\Hour::factory()->create();

    $this->assertDatabaseHas('comments', $hour->toArray());
    expect($hour->comments)->toBeInstanceOf(Comment::class);
    expect($hour->comments())->toBeInstanceOf(MorphMany::class);
});

// also hires, suspensions, terminations, downtimes, productions, hours
