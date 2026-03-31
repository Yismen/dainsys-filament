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
                ->label('ID'),
            ExportColumn::make('date'),
            ExportColumn::make('employee.full_name')
                ->label('Employee Name'),
            ExportColumn::make('termination_type')
                ->label('Termination Type'),
            ExportColumn::make('is_rehireable')
                ->label('Is Rehireable'),
            ExportColumn::make('comment')
                ->label('Comment'),
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
