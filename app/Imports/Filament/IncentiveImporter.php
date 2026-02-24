<?php

namespace App\Imports\Filament;

use App\Models\Incentive;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class IncentiveImporter extends Importer
{
    protected static ?string $model = Incentive::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('payable_date')
                ->requiredMapping()
                ->castStateUsing(fn ($state) => \Illuminate\Support\Carbon::parse($state)->format('Y-m-d'))
                ->rules(['required', 'date']),
            ImportColumn::make('employee_id')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('project_id')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('total_production_hours')
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('total_sales')
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('amount')
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('notes')
                ->rules(['nullable', 'string']),
        ];
    }

    public function resolveRecord(): Incentive
    {
        return Incentive::firstOrNew([
            'payable_date' => $this->data['payable_date'],
            'employee_id' => $this->data['employee_id'],
            'project_id' => $this->data['project_id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your incentives import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
