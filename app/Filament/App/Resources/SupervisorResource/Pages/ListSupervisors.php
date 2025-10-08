<?php

namespace App\Filament\App\Resources\SupervisorResource\Pages;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\CreateAction;
use App\Filament\App\Resources\SupervisorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupervisors extends ListRecords implements HasActions
{
    use InteractsWithActions;
    protected static string $resource = SupervisorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
