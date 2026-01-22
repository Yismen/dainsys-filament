<?php

use App\Console\Commands\RegenerateUuidsForModels;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use App\Jobs\RegenerateIuidForModelJob;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Event::fake();

    Mail::fake();
});

it('runs without error and job is queued', function () {

    Queue::fake([
        RegenerateIuidForModelJob::class,
    ]);

    $user = User::factory()->create();

    $this->assertDatabaseHas(User::class, [
        'id' => $user->id
    ]);

    $command = $this->artisan('dainsys:regenerate-uuids-for-models');
    $command->execute();

    $command->assertExitCode(0);

    Queue::assertPushed(RegenerateIuidForModelJob::class);

    $job = (new RegenerateIuidForModelJob('users'));

    $job->handle();

    $this->assertDatabaseMissing(User::class, [
        'id' => $user->id
    ]);

});

it('changes children uuids when parent is updated', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->for($user, 'owner')->create();

    $this->assertDatabaseHas(Ticket::class, [
        'owner_id' => $user->id,
    ]);

     $this->artisan(RegenerateUuidsForModels::class);

    $this->assertDatabaseMissing(Ticket::class, [
        'owner_id' => $user->id,
    ]);
});

it('works only if app is not in production', function () {
    Queue::fake();
    $user = User::factory()->create();
    $ticket = Ticket::factory()->for($user, 'owner')->create();

    $this->assertDatabaseHas(Ticket::class, [
        'owner_id' => $user->id,
    ]);

    app()['env'] = 'production';

    $this->artisan(RegenerateUuidsForModels::class);

    Queue::assertNotPushed(RegenerateIuidForModelJob::class);
    $this->assertDatabaseHas(Ticket::class, [
        'owner_id' => $user->id,
    ]);

    app()['env'] = 'testing';
});



