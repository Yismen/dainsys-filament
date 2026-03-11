<?php

use App\Filament\HumanResource\Widgets\HiresVsTerminationsChart;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Site;
use App\Models\Supervisor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Assert;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('shows correct hires and terminations per month and respects filters', function (): void {
    $user = User::factory()->create();
    if ($user) {
        actingAs($user);
    }

    $site = Site::factory()->create();
    $project = Project::factory()->create();
    $supervisor = Supervisor::factory()->create();

    // 3 hires and 1 termination last month
    Employee::factory()->count(3)->create([
        'hired_at' => Carbon::now()->subMonth()->startOfMonth()->addDays(1),
        'terminated_at' => null,
        'site_id' => $site->id,
        'project_id' => $project->id,
        'supervisor_id' => $supervisor->id,
    ]);
    Employee::factory()->create([
        'hired_at' => Carbon::now()->subMonth()->startOfMonth()->addDays(2),
        'terminated_at' => Carbon::now()->subMonth()->startOfMonth()->addDays(10),
        'site_id' => $site->id,
        'project_id' => $project->id,
        'supervisor_id' => $supervisor->id,
    ]);
    // 2 hires this month
    Employee::factory()->count(2)->create([
        'hired_at' => Carbon::now()->startOfMonth()->addDays(1),
        'terminated_at' => null,
        'site_id' => $site->id,
        'project_id' => $project->id,
        'supervisor_id' => $supervisor->id,
    ]);

    $component = Livewire::test(HiresVsTerminationsChart::class, [
        'filters' => [
            'site' => [$site->id],
            'project' => [$project->id],
            'supervisor' => [$supervisor->id],
        ],
    ]);
    $reflection = new ReflectionClass($component->instance());
    $method = $reflection->getMethod('getData');
    $method->setAccessible(true);
    $data = $method->invoke($component->instance());
    if (! is_array($data) || ! isset($data['labels'], $data['datasets'][0]['data'], $data['datasets'][1]['data'])) {
        Assert::fail('Chart data structure unexpected: '.json_encode($data));
    }
    $labels = $data['labels'];
    $hires = $data['datasets'][0]['data'];
    $terminations = $data['datasets'][1]['data'];

    // Use the last two labels for this and last month
    $idxLast = count($labels) - 2;
    $idxThis = count($labels) - 1;
    if (! isset($labels[$idxLast], $labels[$idxThis], $hires[$idxLast], $hires[$idxThis], $terminations[$idxLast], $terminations[$idxThis])) {
        Assert::fail('Not enough labels or dataset generated. Labels: '.json_encode($labels).' Hires: '.json_encode($hires).' Terminations: '.json_encode($terminations));
    }
    // 3 hires + 1 (terminated) last month, 2 hires this month
    expect($hires[$idxLast])->toBe(4)
        ->and($terminations[$idxLast])->toBe(1)
        ->and($hires[$idxThis])->toBe(2)
        ->and($terminations[$idxThis])->toBe(0);
});
