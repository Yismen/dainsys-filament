<?php

use App\Models\SocialSecurity;
use App\Events\SocialSecurityCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


test('social securities model interacts with db table', function () {
    Mail::fake();
    $data = SocialSecurity::factory()->make();

    SocialSecurity::create($data->toArray());

    $this->assertDatabaseHas('social_securities', $data->only([
        'employee_id', 'ars_id', 'afp_id', 'number', 'comments'
    ]));
});

test('social security model uses soft delete', function () {
    Mail::fake();
    $socialSecurity = SocialSecurity::factory()->create();

    $socialSecurity->delete();

    $this->assertSoftDeleted(SocialSecurity::class, [
        'id' => $socialSecurity->id
    ]);
});

test('social_securities model belongs to employee', function () {
    Mail::fake();
    $socialSecurity = SocialSecurity::factory()->create();

    expect($socialSecurity->employee)->toBeInstanceOf(\App\Models\Employee::class);
    expect($socialSecurity->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('social_securities model belongs to ars', function () {
    Mail::fake();
    $socialSecurity = SocialSecurity::factory()->create();

    expect($socialSecurity->ars)->toBeInstanceOf(\App\Models\Ars::class);
    expect($socialSecurity->ars())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('social_securities model belongs to afp', function () {
    Mail::fake();
    $socialSecurity = SocialSecurity::factory()->create();

    expect($socialSecurity->afp)->toBeInstanceOf(\App\Models\Afp::class);
    expect($socialSecurity->afp())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

/** @test */
// public function email_is_sent_when_social$socialSecurity_is_created()
// {
//     Mail::fake();
//     SocialSecurity::factory()->create();
//     Mail::assertQueued(MailSocialSecurityCreated::class);
// }
