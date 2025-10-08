<?php

namespace App\Filament\App\Resources\EmployeeResource\Pages;

use Filament\Actions\EditAction;
use App\Filament\App\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEmployee extends ViewRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
