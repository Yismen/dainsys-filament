<?php

namespace App\Filament\Workforce\Resources\DowntimeReasons\Pages;

use App\Filament\Workforce\Resources\DowntimeReasons\DowntimeReasonResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDowntimeReason extends CreateRecord
{
    protected static string $resource = DowntimeReasonResource::class;
}
