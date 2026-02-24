<?php

namespace App\Imports\Filament;

use App\Models\NightlyHour;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;

class NightlyHourImporter extends Importer
{
    protected static ?string $model = NightlyHour::class;

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
            ImportColumn::make('total_hours')
                ->requiredMapping()
                ->rules(['required', 'numeric', 'min:0']),
        ];
    }

    public function resolveRecord(): NightlyHour
    {
        return NightlyHour::firstOrNew([
            'date' => $this->data['date'],
            'employee_id' => $this->data['employee_id'],
        ]);
    }

    public static function getCompletedNotificationBody(\Filament\Actions\Imports\Models\Import $import): string
    {
        $body = 'Your nightly hours import has completed and '.\Illuminate\Support\Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.\Illuminate\Support\Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
