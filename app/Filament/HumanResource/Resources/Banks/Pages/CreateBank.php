<?php

namespace App\Filament\HumanResource\Resources\Banks\Pages;

use App\Filament\HumanResource\Resources\Banks\BankResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBank extends CreateRecord
{
    protected static string $resource = BankResource::class;
}
