<?php

namespace App\Filament\App\Resources\PunchResource\Pages;

use App\Filament\App\Resources\PunchResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePunch extends CreateRecord
{
    protected static string $resource = PunchResource::class;
}
