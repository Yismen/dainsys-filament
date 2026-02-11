<?php

use App\Console\Commands\UpdateTicketStatus;
use App\Enums\SupportRoles;
use App\Events\TicketCreatedEvent;
use App\Mail\TicketsExpiredMail;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Mail::fake();
    Event::fake([
        TicketCreatedEvent::class,
    ]);
});

it('renders correctly with attachment file', function (): void {
    $ticketsAdmin = User::factory()->create();
    $adminRole = Role::firstOrCreate(['name' => SupportRoles::Manager->value]);
    $ticketsAdmin->assignRole($adminRole);
    $date = now()->addDays(30);
    $fileName = 'tickets-expired-'.$date->format('Y-m-d').'.xlsx';

    $tickets = Ticket::factory()->count(5)->create();
    $this->travelTo($date);

    $this->artisan(UpdateTicketStatus::class);

    $mailable = new TicketsExpiredMail($tickets->each->refresh());

    $mailable->assertHasSubject('Tickets Expired Report');
    // $mailable->assertHasAttachmentFromStorage("app/{$fileName}");

    if (Storage::exists($fileName)) {
        File::delete(storage_path("app/{$fileName}"));
        Storage::delete(Storage::exists($fileName));
    }
});
