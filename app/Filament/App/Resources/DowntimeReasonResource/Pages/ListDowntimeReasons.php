<?php

namespace App\Filament\App\Resources\DowntimeReasonResource\Pages;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\CreateAction;
use App\Filament\App\Resources\DowntimeReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDowntimeReasons extends ListRecords implements HasActions
{
    use InteractsWithActions;
    protected static string $resource = DowntimeReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
