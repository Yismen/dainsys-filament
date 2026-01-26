<?php

namespace App\Filament\HumanResource\Resources\HRActivityRequests\Pages;

use App\Filament\HumanResource\Resources\HRActivityRequests\HRActivityRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHRActivityRequests extends ListRecords
{
    protected static string $resource = HRActivityRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
