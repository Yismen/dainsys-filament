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
            ExportColumn::make('internal_id')
                ->label('Internal ID'),
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
            ExportColumn::make('hired_at')
                ->label('Hired At')
                ->state(fn ($record) => $record->hired_at?->format('Y-m-d')),
            ExportColumn::make('site.name')
                ->label('Site'),
            ExportColumn::make('project.name')
                ->label('Project'),
            ExportColumn::make('supervisor.name')
                ->label('Supervisor'),
            ExportColumn::make('position.name')
                ->label('Position'),
            ExportColumn::make('position.salary')
                ->label('Salary'),
            ExportColumn::make('bankAccount.bank.name')
                ->label('Bank Name'),
            ExportColumn::make('bankAccount.account')
                ->label('Bank Account Number'),
            ExportColumn::make('citizenship.name')
                ->label('Citizenship'),
            ExportColumn::make('terminated_at')
                ->label('Terminated At')
                ->state(fn ($record) => $record->terminated_at?->format('Y-m-d')),
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
