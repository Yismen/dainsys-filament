<?php

use App\Models\Employee;
use App\Models\Hire;
use App\Models\Supervisor;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('supervisor'),
    );
});

it('shows upcoming birthdays within ten days', function (): void {
    /** @var User $user */
    $user = User::factory()->create();
    $supervisor = Supervisor::factory()->create([
        'user_id' => $user->id,
    ]);

    $soonBirthday = Employee::factory()->create([
        'date_of_birth' => Carbon::now()->subYears(25)->addDays(5),
    ]);
    Hire::factory()->create([
        'employee_id' => $soonBirthday->id,
        'supervisor_id' => $supervisor->id,
    ]);

    $laterBirthday = Employee::factory()->create([
        'date_of_birth' => Carbon::now()->subYears(30)->addDays(15),
    ]);
    Hire::factory()->create([
        'employee_id' => $laterBirthday->id,
        'supervisor_id' => $supervisor->id,
    ]);

    actingAs($user);

    $response = get(route('filament.supervisor.pages.dashboard'));

    $response->assertOk();
    $response->assertSee($soonBirthday->full_name);
    $response->assertDontSee($laterBirthday->full_name);
});
