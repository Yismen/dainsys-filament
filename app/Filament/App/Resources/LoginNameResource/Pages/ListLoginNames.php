<?php

namespace App\Filament\App\Resources\LoginNameResource\Pages;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\CreateAction;
use App\Filament\App\Resources\LoginNameResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoginNames extends ListRecords implements HasActions
{
    use InteractsWithActions;
    protected static string $resource = LoginNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
