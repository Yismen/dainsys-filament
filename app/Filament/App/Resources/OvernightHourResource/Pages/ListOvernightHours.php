<?php

namespace App\Filament\App\Resources\OvernightHourResource\Pages;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Action;
use Throwable;
use Maatwebsite\Excel\Validators\ValidationException;
use Filament\Actions\CreateAction;
use Filament\Actions;
use App\Imports\OvernightHourImport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\App\Resources\OvernightHourResource;

class ListOvernightHours extends ListRecords implements HasActions
{
    use InteractsWithActions;
    protected static string $resource = OvernightHourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Upload Overnight Hours')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->modalWidth('md')
                ->modalAlignment('center')
                ->modalHeading(fn ($livewire) => __('Import Overnight Excel File'))
                ->modalDescription(__('Import data into database from excel file'))
                ->modalFooterActionsAlignment('right')
                ->schema([
                    FileUpload::make('overnight_hours_file')
                        ->preserveFilenames(true)
                        ->required()
                        ->acceptedFileTypes(['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'csv', 'xls', 'xlsx', 'text/plain'])
                        ->maxSize(1024)
                        ->fetchFileInformation(false)
                        ->storeFiles(false)
                ])
                // ->slideOver()
                ->closeModalByClickingAway(false)
                ->action(function (array $data) {
                    try {
                        Excel::import(
                            new OvernightHourImport(
                                filename: $data['overnight_hours_file']->getClientOriginalName()
                            ),
                            $data['overnight_hours_file']
                        );
                    } catch (Throwable $th) {
                        // $this->validator
                        if ($th instanceof ValidationException) {
                            Notification::make()
                                ->title($th->getMessage())
                                ->danger()
                                ->send();
                        }

                        throw $th;
                    }
                }),
            CreateAction::make(),
        ];
    }
}
