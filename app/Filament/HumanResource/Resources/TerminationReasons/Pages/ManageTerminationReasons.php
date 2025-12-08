<?php

namespace App\Filament\HumanResource\Resources\TerminationReasons\Pages;

use App\Filament\HumanResource\Resources\TerminationReasons\TerminationReasonResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTerminationReasons extends ManageRecords
{
    protected static string $resource = TerminationReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
