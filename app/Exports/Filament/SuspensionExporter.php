<?php

namespace App\Exports\Filament;

use App\Models\Suspension;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class SuspensionExporter extends Exporter
{
    protected static ?string $model = Suspension::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label(__('filament.id')),
            ExportColumn::make('employee.full_name')
                ->label(__('filament.employee_name')),
            ExportColumn::make('suspensionType.name')
                ->label(__('filament.suspension_type')),
            ExportColumn::make('starts_at')
                ->label(__('filament.start_date')),
            ExportColumn::make('ends_at')
                ->label(__('filament.end_date')),
            ExportColumn::make('status')
                ->label(__('filament.status')),
            ExportColumn::make('comment')
                ->label(__('filament.comment')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your suspension export has completed and '.Number::format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
