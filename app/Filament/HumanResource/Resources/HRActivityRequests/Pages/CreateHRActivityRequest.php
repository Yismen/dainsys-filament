<?php

namespace App\Filament\HumanResource\Resources\HRActivityRequests\Pages;

use App\Filament\HumanResource\Resources\HRActivityRequests\HRActivityRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHRActivityRequest extends CreateRecord
{
    protected static string $resource = HRActivityRequestResource::class;
}
