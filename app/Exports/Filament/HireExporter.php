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
                ->label(__('filament.id')),
            ExportColumn::make('date'),
            ExportColumn::make('employee.full_name')
                ->label(__('filament.employee_name')),
            ExportColumn::make('site.name')
                ->label(__('filament.site_name')),
            ExportColumn::make('project.name')
                ->label(__('filament.project_name')),
            ExportColumn::make('position.name')
                ->label(__('filament.position_name')),
            ExportColumn::make('supervisor.name')
                ->label(__('filament.supervisor_name')),
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
