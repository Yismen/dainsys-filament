<?php

namespace App\Filament\App\Resources\PerformanceResource\Pages;

use Filament\Actions;
use App\Imports\PerformanceImport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\App\Resources\PerformanceResource;

class ListPerformances extends ListRecords
{
    protected static string $resource = PerformanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Upload Performance')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->modalWidth('md')
                ->modalAlignment('center')
                ->modalHeading(fn ($livewire) => __('Import Excel'))
                ->modalDescription(__('Import data into database from excel file'))
                ->modalFooterActionsAlignment('right')
                ->form([
                    FileUpload::make('performance_file')
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
                            new PerformanceImport(
                                filename: $data['performance_file']->getClientOriginalName()
                            ),
                            $data['performance_file']
                        );
                    } catch (\Throwable $th) {
                        // $this->validator
                        if ($th instanceof \Maatwebsite\Excel\Validators\ValidationException) {
                            Notification::make()
                                ->title($th->getMessage())
                                ->danger()
                                ->send();
                        }

                        throw $th;
                    }
                }),
            // Actions\CreateAction::make(),
        ];
    }
}
