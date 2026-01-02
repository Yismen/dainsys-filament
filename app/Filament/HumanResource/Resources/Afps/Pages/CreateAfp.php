<?php

namespace App\Filament\HumanResource\Resources\Afps\Pages;

use App\Filament\HumanResource\Resources\Afps\AfpResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAfp extends CreateRecord
{
    protected static string $resource = AfpResource::class;
}
