<?php

namespace App\Imports\Filament;

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
                ->castStateUsing(fn ($state) => \Illuminate\Support\Carbon::parse($state)->format('Y-m-d'))
                ->rules(['required', 'date']),
            ImportColumn::make('employee_id')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('campaign_id')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('conversions')
                ->requiredMapping()
                ->rules(['required', 'numeric']),
            ImportColumn::make('total_time')
                ->requiredMapping()
                ->rules(['required', 'numeric']),
            ImportColumn::make('production_time')
                ->requiredMapping()
                ->rules(['required', 'numeric']),
            ImportColumn::make('talk_time')
                ->requiredMapping()
                ->rules(['required', 'numeric']),
        ];
    }

    public function resolveRecord(): Production
    {
        return Production::firstOrNew([
            'date' => $this->data['date'],
            'employee_id' => $this->data['employee_id'],
            'campaign_id' => $this->data['campaign_id'],
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
