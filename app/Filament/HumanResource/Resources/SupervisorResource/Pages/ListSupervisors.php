<?php

namespace App\Filament\HumanResource\Resources\SupervisorResource\Pages;

use App\Filament\HumanResource\Resources\SupervisorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupervisors extends ListRecords
{
    protected static string $resource = SupervisorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
