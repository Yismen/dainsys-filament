<?php

namespace App\Filament\HumanResource\Resources\Employees\Pages;

use App\Actions\Filament\HireEmployeeAction;
use App\Actions\Filament\SuspendEmployeeAction;
use App\Actions\Filament\TerminateEmployeeAction;
use App\Filament\HumanResource\Resources\Employees\EmployeeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            // DeleteAction::make(),
            // ForceDeleteAction::make(),
            // RestoreAction::make(),
            HireEmployeeAction::make(),
            TerminateEmployeeAction::make(),
            SuspendEmployeeAction::make(),

        ];
    }
}
