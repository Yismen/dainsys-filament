<?php

namespace App\Filament\HumanResource\Resources\TerminationTypes\Pages;

use App\Filament\HumanResource\Resources\TerminationTypes\TerminationTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTerminationTypes extends ManageRecords
{
    protected static string $resource = TerminationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
