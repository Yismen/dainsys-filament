<?php

namespace App\Filament\Support\Resources\MyTickets\Pages;

use App\Filament\Support\Resources\MyTickets\MyTicketResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMyTickets extends ListRecords
{
    protected static string $resource = MyTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
