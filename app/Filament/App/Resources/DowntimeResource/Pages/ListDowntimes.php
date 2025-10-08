<?php

namespace App\Filament\App\Resources\DowntimeResource\Pages;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\CreateAction;
use Filament\Actions;
use App\Imports\DowntimeImport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\App\Resources\DowntimeResource;

class ListDowntimes extends ListRecords implements HasActions
{
    use InteractsWithActions;
    protected static string $resource = DowntimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
