<?php

namespace App\Filament\App\Resources\DepartmentResource\Pages;

use Filament\Actions\EditAction;
use App\Filament\App\Resources\DepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDepartment extends ViewRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
