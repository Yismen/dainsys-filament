<?php

namespace App\Filament\Support\Resources\TicketDepartmentResource\Pages;

use Filament\Actions\EditAction;
use App\Filament\Support\Resources\TicketDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTicketDepartment extends ViewRecord
{
    protected static string $resource = TicketDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
