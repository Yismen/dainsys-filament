<?php

use App\Models\Campaign;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Production;
use App\Models\Site;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;

beforeEach(fn () => Mail::fake());

it('protects the route against unauthorized tokens', function () {
    Sanctum::actingAs(User::factory()->create());

    $response = $this->getJson('/api/productions?date='.now()->format('Y-m-d'));

    $response->assertForbidden();
});

it('returns correct structure', function () {

    Production::factory()->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $response = $this->getJson('/api/productions?date='.now()->format('Y-m-d'));

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'unique_id',
                    'date',
                    'employee_id',
                    'employee_full_name',
                    'campaign_id',
                    'campaign_name',
                    'project_id',
                    'project_name',
                    'client_id',
                    'client_name',
                    'site_id',
                    'site_name',
                    'source_id',
                    'source_name',
                    'supervisor_id',
                    'supervisor_name',
                    'revenue_type',
                    'revenue_rate',
                    'revenue',
                    'sph_goal',
                    'conversions',
                    'total_time',
                    'production_time',
                    'talk_time',
                    'billable_time',
                ],
            ],
        ]);
});

it('date filter is required', function () {
    Production::factory()->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $this->getJson('/api/productions')
        ->assertJsonValidationErrorFor('date');

});
// filters data by date
it('filters by date', function () {

    Production::factory()->create(['date' => now()]);
    Production::factory()->create(['date' => now()->subDays(5)]);
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $reponse = $this->getJson('/api/productions?date='.now())
        ->assertJsonCount(1);

    expect(count($reponse->json()['data']))
        ->tobe(1);
});
// filters data by date range comma separated
it('filters by date range if date is separated by comma', function () {

    Production::factory()->create(['date' => now()]);
    Production::factory()->create(['date' => now()->subMonth()]);
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $reponse = $this->getJson('/api/productions?date='.now()->subDay().','.now());

    expect(count($reponse->json()['data']))
        ->tobe(1);
});
// filters data by campaign
it('filters by campaign', function () {
    $campaign_1 = Campaign::factory()->create();
    $campaign_2 = Campaign::factory()->create();

    Production::factory()->for($campaign_1)->create();
    Production::factory()->for($campaign_2)->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $reponse = $this->getJson('/api/productions?date='.now().'&campaign='.$campaign_1->id);

    expect(count($reponse->json()['data']))
        ->tobe(1);

    $reponse = $this->getJson('/api/productions?date='.now().'&campaign='.$campaign_1->name);

    expect(count($reponse->json()['data']))
        ->tobe(1);
});
// filters data by project
it('filters by project', function () {
    $campaign_1 = Campaign::factory()->create();
    $campaign_2 = Campaign::factory()->create();

    Production::factory()->for($campaign_1)->create();
    Production::factory()->for($campaign_2)->create();

    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $reponse = $this->getJson('/api/productions?date='.now().'&project='.$campaign_1->project->id);

    expect(count($reponse->json()['data']))
        ->tobe(1);

    $reponse = $this->getJson('/api/productions?date='.now().'&project='.$campaign_1->project->name)
        ->assertJsonCount(1);

    expect(count($reponse->json()['data']))
        ->tobe(1);
});
// filters data by employee
it('filters by employee', function () {
    $employee_1 = Employee::factory()->create();
    Employee::factory()->create();

    Production::factory()->for($employee_1)->create();
    Production::factory()->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $reponse = $this->getJson('/api/productions?date='.now().'&employee='.$employee_1->id);

    expect(count($reponse->json()['data']))
        ->tobe(1);

    $reponse = $this->getJson('/api/productions?date='.now().'&employee='.$employee_1->full_name);

    expect(count($reponse->json()['data']))
        ->tobe(1);
});
// filters data by supervisor
it('filters by supervisor', function () {
    $supervisor = Supervisor::factory()->create();
    $employee = Employee::factory()->create();
    Hire::factory()
        ->for($supervisor)
        ->for($employee)
        ->create();

    Production::factory()->for($employee)->create();
    Production::factory(2)->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

    $reponse = $this->getJson('/api/productions?date='.now().'&supervisor='.$supervisor->id);

    expect(count($reponse->json()['data']))
        ->tobe(1);

    $reponse = $this->getJson('/api/productions?date='.now().'&supervisor='.$supervisor->name);

    expect(count($reponse->json()['data']))
        ->tobe(1);
});
// filters data by site
// it('filters by site', function () {
//     $this->withoutExceptionHandling();
//     $site = Site::factory()->create();
//     $employee = Employee::factory()->create();
//     Hire::factory()
//         ->for($site)
//         ->for($employee)
//         ->create();

//     Production::factory()->for($employee)->create();
//     Production::factory(2)->create();
//     Sanctum::actingAs(user: User::factory()->create(), abilities: ['use-dainsys']);

//     $reponse = $this->getJson('/api/productions?date=' . now() . '&site=' . $site->id);

//     expect(count($reponse->json()['data']))
//         ->tobe(1);

//     $reponse = $this->getJson('/api/productions?date=' . now() . '&site=' . $site->name);

//     expect(count($reponse->json()['data']))
//         ->tobe(1);
// });
