<?php

namespace App\Filament\OperationsDirector\Resources\Absences\Pages;

use App\Filament\OperationsDirector\Resources\Absences\AbsenceResource;
use Filament\Resources\Pages\ListRecords;

class ListAbsences extends ListRecords
{
    protected static string $resource = AbsenceResource::class;
}
