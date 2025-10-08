<?php

namespace App\Filament\App\Resources\AfpResource\Pages;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\CreateAction;
use App\Filament\App\Resources\AfpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAfps extends ListRecords implements HasActions
{
    use InteractsWithActions;
    protected static string $resource = AfpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
