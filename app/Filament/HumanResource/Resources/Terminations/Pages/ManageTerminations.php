<?php

namespace App\Filament\HumanResource\Resources\Terminations\Pages;

use App\Filament\HumanResource\Resources\Terminations\TerminationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTerminations extends ManageRecords
{
    protected static string $resource = TerminationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
