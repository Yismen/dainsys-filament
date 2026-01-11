<?php

namespace App\Filament\Imports;

use App\Models\Production;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class ProductionImporter extends Importer
{
    protected static ?string $model = Production::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('date')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('employee')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('campaign')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            // ImportColumn::make('revenue_type')
            //     ->rules(['max:255']),
            // ImportColumn::make('supervisor')
            //     ->relationship(),
            // ImportColumn::make('revenue_rate')
            //     ->requiredMapping()
            //     ->numeric()
            //     ->rules(['required', 'float']),
            // ImportColumn::make('sph_goal')
            //     ->requiredMapping()
            //     ->numeric()
            //     ->rules(['required', 'float']),
            ImportColumn::make('conversions')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'float']),
            ImportColumn::make('total_time')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'float']),
            ImportColumn::make('production_time')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'float']),
            ImportColumn::make('talk_time')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'float']),
            // ImportColumn::make('converted_to_payroll_at')
            //     ->rules(['datetime']),
        ];
    }

    public function resolveRecord(): Production
    {
        return Production::firstOrNew([
            'campaign_id' => $this->data['campaign_id'],
            'employee_id' => $this->data['employee_id'],
            'date' => $this->data['date'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your production import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
