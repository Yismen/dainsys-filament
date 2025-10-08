<?php

namespace App\Filament\Support\Resources\TicketDepartmentResource\Pages;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\CreateAction;
use App\Filament\Support\Resources\TicketDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTicketDepartments extends ListRecords implements HasActions
{
    use InteractsWithActions;
    protected static string $resource = TicketDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
