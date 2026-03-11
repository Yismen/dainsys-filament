<?php

namespace App\Filament\HumanResource\Resources\Absences\Pages;

use App\Filament\HumanResource\Resources\Absences\AbsenceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAbsences extends ListRecords
{
    protected static string $resource = AbsenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
