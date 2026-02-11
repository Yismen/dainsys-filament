<?php

use App\Filament\Imports\PayrollImporter;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\User;
use Filament\Actions\Imports\Models\Import as ImportModel;

function setPayrollImporterData(PayrollImporter $importer, array $data): void
{
    $property = new ReflectionProperty($importer, 'data');
    $property->setAccessible(true);
    $property->setValue($importer, $data);
}

it('resolves existing payrolls by payable_date and employee_id', function (): void {
    $employee = Employee::factory()->create();
    $payableDate = now()->format('Y-m-d');

    $payroll = Payroll::factory()->create([
        'payable_date' => $payableDate,
        'employee_id' => $employee->id,
    ]);

    $import = ImportModel::create([
        'file_name' => 'payrolls.xlsx',
        'file_path' => 'payrolls.xlsx',
        'importer' => PayrollImporter::class,
        'processed_rows' => 0,
        'total_rows' => 1,
        'successful_rows' => 0,
        'user_id' => User::factory()->create()->id,
    ]);

    $importer = new PayrollImporter($import, [], []);

    setPayrollImporterData($importer, [
        'payable_date' => $payableDate,
        'employee_id' => $employee->id,
    ]);

    $record = $importer->resolveRecord();

    expect($record->is($payroll))->toBeTrue();
});

it('creates a new payroll when no matching record exists', function (): void {
    $employee = Employee::factory()->create();
    $payableDate = now()->subDay()->format('Y-m-d');

    $import = ImportModel::create([
        'file_name' => 'payrolls.xlsx',
        'file_path' => 'payrolls.xlsx',
        'importer' => PayrollImporter::class,
        'processed_rows' => 0,
        'total_rows' => 1,
        'successful_rows' => 0,
        'user_id' => User::factory()->create()->id,
    ]);

    $importer = new PayrollImporter($import, [], []);

    setPayrollImporterData($importer, [
        'payable_date' => $payableDate,
        'employee_id' => $employee->id,
    ]);

    $record = $importer->resolveRecord();

    expect($record->exists)->toBeFalse();
    expect($record->payable_date)->toBe($payableDate);
    expect($record->employee_id)->toBe($employee->id);
});
