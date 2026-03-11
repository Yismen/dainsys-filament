use Illuminate\Contracts\Auth\Authenticatable;
<?php

use App\Filament\HumanResource\Widgets\MonthlyAttritionChart;
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

describe('MonthlyAttritionChart Widget', function (): void {
    uses(RefreshDatabase::class);

    it('shows correct attrition percentage for each month and respects filters', function (): void {
        $user = User::factory()->create();
        if ($user) {
            actingAs($user);
        }

        $site = Site::factory()->create();
        $project = Project::factory()->create();
        $supervisor = Supervisor::factory()->create();

        // 10 employees at start of last month
        Employee::factory()->count(10)->create([
            'hired_at' => Carbon::now()->subMonths(2)->startOfMonth(),
            'terminated_at' => null,
            'site_id' => $site->id,
            'project_id' => $project->id,
            'supervisor_id' => $supervisor->id,
        ]);
        // 2 terminations last month
        Employee::factory()->count(2)->create([
            'hired_at' => Carbon::now()->subMonths(2)->startOfMonth(),
            'terminated_at' => Carbon::now()->subMonth()->startOfMonth()->addDays(2),
            'site_id' => $site->id,
            'project_id' => $project->id,
            'supervisor_id' => $supervisor->id,
        ]);
        // 1 hire last month (should be included in headcount for this month)
        Employee::factory()->create([
            'hired_at' => Carbon::now()->subMonth()->startOfMonth()->addDays(1),
            'terminated_at' => null,
            'site_id' => $site->id,
            'project_id' => $project->id,
            'supervisor_id' => $supervisor->id,
        ]);

        $component = Livewire::test(MonthlyAttritionChart::class, [
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
        if (! is_array($data) || ! isset($data['labels'], $data['datasets'][0]['data'])) {
            Assert::fail('Chart data structure unexpected: '.json_encode($data));
        }
        $labels = $data['labels'];
        $dataset = $data['datasets'][0]['data'];

        // Use the second-to-last label, which should correspond to last month
        $idx = count($labels) - 2;
        $month = $labels[$idx] ?? null;
        if (! isset($labels[$idx]) || ! isset($dataset[$idx])) {
            Assert::fail('Not enough labels or dataset generated. Labels: '.json_encode($labels).' Dataset: '.json_encode($dataset).' Idx: '.$idx);
        }
        $month = $labels[$idx];
        // 2 terminations, 11 headcount (10 + 1 new hire)
        expect($dataset[$idx])->toBe(round((2 / 11) * 100, 2), "Attrition for '$month' should be correct");
    });
});
