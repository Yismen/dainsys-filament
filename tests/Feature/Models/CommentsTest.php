<?php

use App\Models\Comment;
use App\Models\Downtime;
use App\Models\Hire;
use App\Models\Suspension;
use App\Models\Termination;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

beforeEach(function (): void {
    Mail::fake();
    Event::fake();
});

test('comment model interacts with db table', function (): void {
    $data = Comment::factory()->create();

    $this->assertDatabaseHas('comments', $data->only([
        'text', 'commentable_id', 'commentable_type',
    ]));
});

test('models are commentable', function (string $modelClass): void {
    $model = $modelClass::factory()->create();

    Comment::factory()->create([
        'commentable_id' => $model->id,
        'commentable_type' => $modelClass,
    ]);

    expect($model->comments->first())->toBeInstanceOf(Comment::class);
    expect($model->comments())->toBeInstanceOf(MorphMany::class);
})->with([
    Hire::class,
    Termination::class,
    Suspension::class,
    Downtime::class,
    // \App\Models\Absence::class,
    // \App\Models\HumanResourceRequest::class,
    // \App\Models\Universal::class,
    // \App\Models\SocialSecurity::class,
]);
