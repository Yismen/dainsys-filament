<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords implements HasActions
{
    use InteractsWithActions;

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
