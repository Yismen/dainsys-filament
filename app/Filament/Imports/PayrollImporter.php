<?php

namespace App\Filament\Imports;

use App\Models\Payroll;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class PayrollImporter extends Importer
{
    protected static ?string $model = Payroll::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('payable_date')
                ->requiredMapping()
                ->castStateUsing(fn ($state) => \Illuminate\Support\Carbon::parse($state)->format('Y-m-d'))
                ->rules(['required', 'date']),
            ImportColumn::make('salary_rate')
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('total_hours')
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('salary_income')
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('medical_licence')
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('gross_income')
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('deduction_ars')
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('deduction_afp')
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('deductions_other')
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('total_deductions')
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('nightly_incomes')
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('overtime_incomes')
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('holiday_incomes')
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('additional_incentives_1')
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('additional_incentives_2')
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('net_payroll')
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('total_payroll')
                ->rules(['nullable', 'numeric', 'min:0']),
        ];
    }

    public function resolveRecord(): Payroll
    {
        return Payroll::firstOrNew([
            'payable_date' => $this->data['payable_date'],
            'employee_id' => $this->data['employee_id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your payrolls import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
