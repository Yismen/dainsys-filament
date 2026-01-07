<?php

namespace App\Filament\HumanResource\Resources\Hires\Pages;

use App\Filament\HumanResource\Resources\Hires\HireResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHire extends CreateRecord
{
    protected static string $resource = HireResource::class;
}
