<?php

namespace App\Filament\HumanResource\Resources\Employees\Pages;

use App\Enums\EmployeeStatuses;
use App\Filament\Actions\HireEmployeeAction;
use App\Filament\Actions\SuspendEmployeeAction;
use App\Filament\Actions\TerminateEmployeeAction;
use App\Filament\HumanResource\Resources\Employees\EmployeeResource;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Project;
use App\Models\Site;
use App\Models\Supervisor;
use App\Services\ModelListService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
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
