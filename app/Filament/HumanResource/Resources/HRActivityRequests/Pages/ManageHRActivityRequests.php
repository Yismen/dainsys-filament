<?php

namespace App\Filament\HumanResource\Resources\HRActivityRequests\Pages;

use App\Filament\HumanResource\Resources\HRActivityRequests\HRActivityRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageHRActivityRequests extends ManageRecords
{
    protected static string $resource = HRActivityRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
