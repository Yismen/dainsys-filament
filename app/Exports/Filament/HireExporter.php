<?php

namespace App\Exports\Filament;

use App\Models\Hire;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class HireExporter extends Exporter
{
    protected static ?string $model = Hire::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('date'),
            ExportColumn::make('employee.full_name')
                ->label('Employee Name'),
            ExportColumn::make('site.name')
                ->label('Site Name'),
            ExportColumn::make('project.name')
                ->label('Project Name'),
            ExportColumn::make('position.name')
                ->label('Position Name'),
            ExportColumn::make('supervisor.name')
                ->label('Supervisor Name'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your hire export has completed and '.Number::format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
