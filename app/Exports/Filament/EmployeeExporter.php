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
                ->label(__('filament.id')),
            ExportColumn::make('full_name')
                ->label(__('filament.full_name')),
            ExportColumn::make('personal_id_type')
                ->label(__('filament.personal_id_type')),
            ExportColumn::make('personal_id')
                ->label(__('filament.personal_id')),
            ExportColumn::make('internal_id')
                ->label(__('filament.internal_id')),
            ExportColumn::make('date_of_birth')
                ->label(__('filament.date_of_birth')),
            ExportColumn::make('cellphone')
                ->label(__('filament.cellphone')),
            ExportColumn::make('secondary_phone')
                ->label(__('filament.secondary_phone')),
            ExportColumn::make('email')
                ->label(__('filament.email')),
            ExportColumn::make('address')
                ->label(__('filament.address')),
            ExportColumn::make('status')
                ->label(__('filament.status')),
            ExportColumn::make('gender')
                ->label(__('filament.gender')),
            ExportColumn::make('has_kids')
                ->label(__('filament.has_kids')),
            ExportColumn::make('hired_at')
                ->label(__('filament.hired_at'))
                ->state(fn ($record) => $record->hired_at?->format('Y-m-d')),
            ExportColumn::make('site.name')
                ->label(__('filament.site')),
            ExportColumn::make('project.name')
                ->label(__('filament.project')),
            ExportColumn::make('supervisor.name')
                ->label(__('filament.supervisor')),
            ExportColumn::make('position.name')
                ->label(__('filament.position')),
            ExportColumn::make('position.salary')
                ->label(__('filament.salary')),
            ExportColumn::make('bankAccount.bank.name')
                ->label(__('filament.bank_name')),
            ExportColumn::make('bankAccount.account')
                ->label(__('filament.bank_account_number')),
            ExportColumn::make('citizenship.name')
                ->label(__('filament.citizenship')),
            ExportColumn::make('terminated_at')
                ->label(__('filament.terminated_at'))
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
