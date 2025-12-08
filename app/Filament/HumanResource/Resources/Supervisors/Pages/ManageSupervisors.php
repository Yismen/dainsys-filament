<?php

namespace App\Filament\HumanResource\Resources\Supervisors\Pages;

use App\Filament\HumanResource\Resources\Supervisors\SupervisorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSupervisors extends ManageRecords
{
    protected static string $resource = SupervisorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
