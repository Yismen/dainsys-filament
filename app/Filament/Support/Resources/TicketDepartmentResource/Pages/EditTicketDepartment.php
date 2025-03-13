<?php

namespace App\Filament\Support\Resources\TicketDepartmentResource\Pages;

use App\Filament\Support\Resources\TicketDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicketDepartment extends EditRecord
{
    protected static string $resource = TicketDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
