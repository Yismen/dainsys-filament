<?php

namespace App\Exports\Filament;

use App\Models\Employee;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class EmployeeExporter extends Exporter
{
    protected static ?string $model = Employee::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('full_name')
                ->label('Full Name'),
            ExportColumn::make('personal_id_type')
                ->label('Personal ID Type'),
            ExportColumn::make('personal_id')
                ->label('Personal ID'),
            ExportColumn::make('date_of_birth')
                ->label('Date of Birth'),
            ExportColumn::make('cellphone')
                ->label('Cellphone'),
            ExportColumn::make('secondary_phone')
                ->label('Secondary Phone'),
            ExportColumn::make('email')
                ->label('Email'),
            ExportColumn::make('address')
                ->label('Address'),
            ExportColumn::make('status')
                ->label('Status'),
            ExportColumn::make('gender')
                ->label('Gender'),
            ExportColumn::make('has_kids')
                ->label('Has Kids'),
            ExportColumn::make('citizenship.name')
                ->label('Citizenship'),
            ExportColumn::make('internal_id')
                ->label('Internal ID'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your employee export has completed and '.Number::format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
