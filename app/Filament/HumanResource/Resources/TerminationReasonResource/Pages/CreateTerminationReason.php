<?php

namespace App\Filament\HumanResource\Resources\TerminationReasonResource\Pages;

use App\Filament\HumanResource\Resources\TerminationReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTerminationReason extends CreateRecord
{
    protected static string $resource = TerminationReasonResource::class;
}
