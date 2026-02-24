<?php

use App\Imports\Filament\IncentiveImporter;
use App\Models\Employee;
use App\Models\Incentive;
use App\Models\Project;
use App\Models\User;
use Filament\Actions\Imports\Models\Import as ImportModel;

function setImporterData(IncentiveImporter $importer, array $data): void
{
    $property = new ReflectionProperty($importer, 'data');
    $property->setAccessible(true);
    $property->setValue($importer, $data);
}

it('resolves existing incentives by payable_date, employee_id, and project_id', function (): void {
    $employee = Employee::factory()->create();
    $project = Project::factory()->create();
    $payableDate = now()->format('Y-m-d');

    $incentive = Incentive::factory()->create([
        'payable_date' => $payableDate,
        'employee_id' => $employee->id,
        'project_id' => $project->id,
    ]);

    $import = ImportModel::create([
        'file_name' => 'incentives.xlsx',
        'file_path' => 'incentives.xlsx',
        'importer' => IncentiveImporter::class,
        'processed_rows' => 0,
        'total_rows' => 1,
        'successful_rows' => 0,
        'user_id' => User::factory()->create()->id,
    ]);

    $importer = new IncentiveImporter($import, [], []);

    setImporterData($importer, [
        'payable_date' => $payableDate,
        'employee_id' => $employee->id,
        'project_id' => $project->id,
    ]);

    $record = $importer->resolveRecord();

    expect($record->is($incentive))->toBeTrue();
});

it('creates a new incentive when no matching record exists', function (): void {
    $employee = Employee::factory()->create();
    $project = Project::factory()->create();
    $payableDate = now()->subDay()->format('Y-m-d');

    $import = ImportModel::create([
        'file_name' => 'incentives.xlsx',
        'file_path' => 'incentives.xlsx',
        'importer' => IncentiveImporter::class,
        'processed_rows' => 0,
        'total_rows' => 1,
        'successful_rows' => 0,
        'user_id' => User::factory()->create()->id,
    ]);

    $importer = new IncentiveImporter($import, [], []);

    setImporterData($importer, [
        'payable_date' => $payableDate,
        'employee_id' => $employee->id,
        'project_id' => $project->id,
    ]);

    $record = $importer->resolveRecord();

    expect($record->exists)->toBeFalse();
    expect($record->payable_date)->toBe($payableDate);
    expect($record->employee_id)->toBe($employee->id);
    expect($record->project_id)->toBe($project->id);
});
