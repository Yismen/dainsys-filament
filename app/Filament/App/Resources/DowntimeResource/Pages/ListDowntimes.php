<?php

namespace App\Filament\App\Resources\DowntimeResource\Pages;

use Filament\Actions;
use App\Imports\DowntimeImport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\App\Resources\DowntimeResource;

class ListDowntimes extends ListRecords
{
    protected static string $resource = DowntimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
