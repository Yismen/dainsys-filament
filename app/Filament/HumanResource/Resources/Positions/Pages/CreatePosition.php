<?php

namespace App\Filament\HumanResource\Resources\Positions\Pages;

use App\Filament\HumanResource\Resources\Positions\PositionResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePosition extends CreateRecord
{
    protected static string $resource = PositionResource::class;
}
