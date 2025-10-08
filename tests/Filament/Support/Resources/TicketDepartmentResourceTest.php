<?php

namespace Tests\Filament\Support\Resources;

use Tests\TestCase;
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
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filament\Support\Resources\TicketDepartmentResource;
use App\Filament\Support\Resources\TicketDepartmentResource\Pages\EditTicketDepartment;
use App\Filament\Support\Resources\TicketDepartmentResource\Pages\ViewTicketDepartment;
use App\Filament\Support\Resources\TicketDepartmentResource\Pages\ListTicketDepartments;
use App\Filament\Support\Resources\TicketDepartmentResource\Pages\CreateTicketDepartment;

class TicketDepartmentResourceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles/permissions if applicable
        Filament::setCurrentPanel(
            Filament::getPanel('support'), // Where `app` is the ID of the panel you want to test.
        );
    }

    // Guests

    /** @test */
    public function regular_ticket_departments_are_forbiden()
    {
        $ticket_departments = TicketDepartment::factory()->count(10)->create();

        $this->actingAs(User::factory()->create())
            ->get(TicketDepartmentResource::getUrl('index'))
            ->assertForbidden();
    }
    /** @test */
    public function regular_users_are_forbiden_to_create_ticket_departments()
    {
        $this->actingAs(User::factory()->create())
            ->get(TicketDepartmentResource::getUrl('create'))
            ->assertForbidden();
    }
    public function regular_users_are_forbiden_to_store_ticket_departments()
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateTicketDepartment::class)
            ->assertForbidden();
    }
    public function regular_users_are_forbiden_to_update_ticket_departments()
    {
        $ticket_department = TicketDepartment::factory()->create();

        $this->actingAs(User::factory()->create());

        Livewire::test(EditTicketDepartment::class, ['record' => $ticket_department->getKey()])
            ->assertForbidden();
    }

    /** @test */
    public function regular_users_are_forbiden_to_edit_ticket_departments()
    {
        $ticket_department = TicketDepartment::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get(TicketDepartmentResource::getUrl('edit', ['record' => $ticket_department->getKey()]))
            ->assertForbidden();
    }

    /** @test */
    public function regular_users_are_forbiden_to_view_ticket_departments()
    {
        $ticket_department = TicketDepartment::factory()->create();

        $this->actingAs(User::factory()->create());

        Livewire::test(ViewTicketDepartment::class, ['record' => $ticket_department->getKey()])
            ->assertForbidden();
    }

    // Authorized

    /** @test */
    public function ticket_departments_index_page_displays_correctly()
    {
        $ticket_departments = TicketDepartment::factory()->count(10)->create();

        $this->actingAs($this->createUserWithPermissionTo('view-any TicketDepartment'))
            ->get(TicketDepartmentResource::getUrl('index'))
            ->assertStatus(200)
            ->assertSee($ticket_departments->first()->name);
    }

    /** @test */
    public function can_create_a_ticket_department()
    {
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
    }

    /** @test */
    public function cannot_create_ticket_department_with_invalid_data()
    {
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
    }

    /** @test */
    public function can_edit_a_ticket_department()
    {
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
    }

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
}
