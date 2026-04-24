<?php

use App\Enums\EmployeeStatuses;
use App\Events\EmployeeHiredEvent;
use App\Events\EmployeeReactivatedEvent;
use App\Events\EmployeeSuspendedEvent;
use App\Events\EmployeeTerminatedEvent;
use App\Listeners\SendEmployeeHiredEmail;
use App\Listeners\SendEmployeeReactivatedEmail;
use App\Listeners\SendEmployeeSuspendedEmail;
use App\Listeners\SendEmployeeTerminatedEmail;
use App\Mail\EmployeeHiredMail;
use App\Mail\EmployeeReactivatedMail;
use App\Mail\EmployeeSuspendedMail;
use App\Mail\EmployeeTerminatedMail;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Mailable;
use App\Models\Suspension;
use App\Models\Termination;
use App\Models\User;
use App\Notifications\Employees\EmployeeHiredNotification;
use App\Notifications\Employees\EmployeeReactivatedNotification;
use App\Notifications\Employees\EmployeeSuspendedNotification;
use App\Notifications\Employees\EmployeeTerminatedNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

beforeEach(function (): void {
    Notification::fake();
});

function subscribeUserToMailable(string $mailableClass): User
{
    $mailable = Mailable::query()->firstOrCreate(
        ['name' => $mailableClass],
        ['description' => $mailableClass]
    );

    $user = User::factory()->create();
    $user->mailables()->attach($mailable);

    Cache::forget('mailing_subscriptions_for_mailable_'.$mailableClass);

    return $user;
}

it('sends hired notification to subscribed users', function (): void {
    $subscriber = subscribeUserToMailable(EmployeeHiredMail::class);
    $hire = Hire::factory()->create();

    (new SendEmployeeHiredEmail)->handle(new EmployeeHiredEvent($hire));

    Notification::assertSentTo($subscriber, EmployeeHiredNotification::class);
});

it('sends suspended notification to subscribed users', function (): void {
    $subscriber = subscribeUserToMailable(EmployeeSuspendedMail::class);
    $hire = Hire::factory()->create();
    $hire->employee->update(['status' => EmployeeStatuses::Hired]);
    $suspension = Suspension::factory()->create([
        'employee_id' => $hire->employee_id,
    ]);

    (new SendEmployeeSuspendedEmail)->handle(new EmployeeSuspendedEvent($suspension));

    Notification::assertSentTo($subscriber, EmployeeSuspendedNotification::class);
});

it('sends reactivated notification to subscribed users', function (): void {
    $subscriber = subscribeUserToMailable(EmployeeReactivatedMail::class);
    $employee = Employee::factory()->create();

    (new SendEmployeeReactivatedEmail)->handle(new EmployeeReactivatedEvent($employee));

    Notification::assertSentTo($subscriber, EmployeeReactivatedNotification::class);
});

it('sends terminated notification to subscribed users', function (): void {
    $subscriber = subscribeUserToMailable(EmployeeTerminatedMail::class);
    $hire = Hire::factory()->create();
    $hire->employee->update(['status' => EmployeeStatuses::Hired]);
    $termination = Termination::factory()->create([
        'employee_id' => $hire->employee_id,
    ]);

    (new SendEmployeeTerminatedEmail)->handle(new EmployeeTerminatedEvent($termination));

    Notification::assertSentTo($subscriber, EmployeeTerminatedNotification::class);
});
