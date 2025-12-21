<?php

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
    Event::fake();
});

test('comment model interacts with db table', function () {
    $data = Comment::factory()->create();

    $this->assertDatabaseHas('comments', $data->only([
        'text', 'commentable_id', 'commentable_type',
    ]));
});

test('models are commentable', function (string $modelClass) {
    $model = $modelClass::factory()->create();

    Comment::factory()->create([
        'commentable_id' => $model->id,
        'commentable_type' => $modelClass,
    ]);

    expect($model->comments->first())->toBeInstanceOf(Comment::class);
    expect($model->comments())->toBeInstanceOf(MorphMany::class);
})->with([
    \App\Models\Hire::class,
    \App\Models\Termination::class,
    \App\Models\Suspension::class,
    \App\Models\Downtime::class,
    // \App\Models\Absence::class,
    // \App\Models\HumanResourceRequest::class,
    // \App\Models\Universal::class,
    // \App\Models\SocialSecurity::class,
]);
