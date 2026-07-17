<?php

namespace App\Exports\Filament;

use App\Models\Termination;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class TerminationExporter extends Exporter
{
    protected static ?string $model = Termination::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label(__('filament.id')),
            ExportColumn::make('date'),
            ExportColumn::make('employee.full_name')
                ->label(__('filament.employee_name')),
            ExportColumn::make('termination_type')
                ->label(__('filament.termination_type')),
            ExportColumn::make('is_rehireable')
                ->label(__('filament.is_rehireable')),
            ExportColumn::make('comment')
                ->label(__('filament.comment')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your termination export has completed and '.Number::format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
