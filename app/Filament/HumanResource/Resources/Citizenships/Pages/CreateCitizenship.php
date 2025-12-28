<?php

namespace App\Filament\HumanResource\Resources\Citizenships\Pages;

use App\Filament\HumanResource\Resources\Citizenships\CitizenshipResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCitizenship extends CreateRecord
{
    protected static string $resource = CitizenshipResource::class;
}
