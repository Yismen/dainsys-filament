<?php

namespace App\Filament\App\Resources\CitizenshipResource\Pages;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\CreateAction;
use App\Filament\App\Resources\CitizenshipResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCitizenships extends ListRecords implements HasActions
{
    use InteractsWithActions;
    protected static string $resource = CitizenshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
