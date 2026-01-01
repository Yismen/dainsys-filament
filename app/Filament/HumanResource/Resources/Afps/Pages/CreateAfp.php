<?php

namespace App\Filament\HumanResource\Resources\Afps\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\HumanResource\Resources\Afps\AfpResource;

class CreateAfp extends CreateRecord
{
    protected static string $resource = AfpResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        dd($data);
        return static::getModel()::create($data);
    }
}
