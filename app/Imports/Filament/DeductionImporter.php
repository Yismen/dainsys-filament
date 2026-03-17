<?php

namespace App\Imports\Filament;

use App\Models\Deduction;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class DeductionImporter extends Importer
{
    protected static ?string $model = Deduction::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('payable_date')
                ->requiredMapping()
                ->castStateUsing(fn ($state) => Carbon::parse($state)->format('Y-m-d'))
                ->rules(['required', 'date']),
            ImportColumn::make('employee_id')
                ->requiredMapping()
                ->rules(['required', 'exists:employees,id']),
            ImportColumn::make('amount')
                ->requiredMapping()
                ->rules(['required', 'numeric', 'min:0']),
            ImportColumn::make('description')
                ->requiredMapping()
                ->rules(['required', 'string']),
        ];
    }

    public function resolveRecord(): Deduction
    {
        return Deduction::firstOrNew([
            'payable_date' => $this->data['payable_date'],
            'employee_id' => $this->data['employee_id'],
            'description' => $this->data['description'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your deductions import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
