<?php

namespace App\Filament\HumanResource\Resources\Absences\Pages;

use App\Filament\HumanResource\Resources\Absences\AbsenceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAbsence extends CreateRecord
{
    protected static string $resource = AbsenceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }
}
