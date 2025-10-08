<?php

use App\Models\User;
use Livewire\Livewire;
use Filament\Facades\Filament;
use App\Models\TicketDepartment;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Cache;
use Filament\Resources\Pages\EditRecord;
use Spatie\Permission\Models\Permission;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Support\Resources\TicketDepartmentResource;
use App\Filament\Support\Resources\TicketDepartmentResource\Pages\EditTicketDepartment;
use App\Filament\Support\Resources\TicketDepartmentResource\Pages\ViewTicketDepartment;
use App\Filament\Support\Resources\TicketDepartmentResource\Pages\ListTicketDepartments;
use App\Filament\Support\Resources\TicketDepartmentResource\Pages\CreateTicketDepartment;




uses(\Illuminate\Foundation\Testing\WithFaker::class);

beforeEach(function () {
    // Seed roles/permissions if applicable
    Filament::setCurrentPanel(
        Filament::getPanel('support'), // Where `app` is the ID of the panel you want to test.
    );
});

test('regular ticket departments are forbiden', function () {
    $ticket_departments = TicketDepartment::factory()->count(10)->create();

    $this->actingAs(User::factory()->create())
        ->get(TicketDepartmentResource::getUrl('index'))
        ->assertForbidden();
});

test('regular users are forbiden to create ticket departments', function () {
    $this->actingAs(User::factory()->create())
        ->get(TicketDepartmentResource::getUrl('create'))
        ->assertForbidden();
});
function regular_users_are_forbiden_to_store_ticket_departments()
{
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateTicketDepartment::class)
        ->assertForbidden();
}
function regular_users_are_forbiden_to_update_ticket_departments()
{
    $ticket_department = TicketDepartment::factory()->create();

    $this->actingAs(User::factory()->create());

    Livewire::test(EditTicketDepartment::class, ['record' => $ticket_department->getKey()])
        ->assertForbidden();
}

test('regular users are forbiden to edit ticket departments', function () {
    $ticket_department = TicketDepartment::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(TicketDepartmentResource::getUrl('edit', ['record' => $ticket_department->getKey()]))
        ->assertForbidden();
});

test('regular users are forbiden to view ticket departments', function () {
    $ticket_department = TicketDepartment::factory()->create();

    $this->actingAs(User::factory()->create());

    Livewire::test(ViewTicketDepartment::class, ['record' => $ticket_department->getKey()])
        ->assertForbidden();
});

test('ticket departments index page displays correctly', function () {
    $ticket_departments = TicketDepartment::factory()->count(10)->create();

    $this->actingAs($this->createUserWithPermissionTo('view-any TicketDepartment'))
        ->get(TicketDepartmentResource::getUrl('index'))
        ->assertStatus(200)
        ->assertSee($ticket_departments->first()->name);
});

test('can create a ticket department', function () {
    $data = [
        'name' => $this->faker->name(),
        'description' => $this->faker->paragraph(),
    ];

    $this->actingAs($this->createUserWithPermissionsToActions(
        ['view-any', 'create'],
        'TicketDepartment'
    ));

    Livewire::test(CreateTicketDepartment::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasNoErrors();

    $this->assertDatabaseHas(TicketDepartment::class, $data);
});

test('cannot create ticket department with invalid data', function () {
    $data = [
        // 'name' => $this->faker->name(),
        'description' => $this->faker->paragraph(),
    ];

    $this->actingAs($this->createUserWithPermissionsToActions(
        ['view-any', 'create'],
        'TicketDepartment'
    ));

    Livewire::test(CreateTicketDepartment::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

test('can edit a ticket department', function () {
    $ticket_department = TicketDepartment::factory()->create();
    $data = [
        'name' => $this->faker->name(),
        'description' => $this->faker->paragraph(),
    ];

    $this->actingAs($this->createUserWithPermissionsToActions(
        ['view-any', 'update'],
        'TicketDepartment'
    ));

    Livewire::test(EditTicketDepartment::class, ['record' => $ticket_department->getKey()])
        ->fillForm($data)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas(TicketDepartment::class, ['id' => $ticket_department->id, 'name' => $data['name']]);
});

/** @test */
// public function can_delete_a_ticket_department()
// {
//     $ticket_department = TicketDepartment::factory()->create();
//     $data = [
//         'name' => $this->faker->name(),
//         'description' => $this->faker->paragraph(),
//     ];
//     $this->actingAs($this->createUserWithPermissionsToActions(
//         ['view-any', 'update'],
//         'TicketDepartment'
//     ));
//     Livewire::test(EditTicketDepartment::class, ['record' => $ticket_department->getKey()])
//         ->callAction(DeleteAction::class);
//     $this->assertSoftDeleted(TicketDepartment::class, ['id' => $ticket_department->id]);
// }
