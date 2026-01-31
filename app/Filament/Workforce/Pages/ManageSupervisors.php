<?php

namespace App\Filament\Workforce\Pages;

use BackedEnum;
use App\Models\Employee;
use Filament\Pages\Page;
use App\Models\Supervisor;
use Filament\Actions\Action;
use Livewire\Attributes\Computed;
use App\Services\ModelListService;
use Illuminate\Support\Collection;
use App\Models\Scopes\IsActiveScope;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class ManageSupervisors extends Page
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    protected string $view = 'filament.workforce.pages.manage-supervisors';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBattery50;

    public array $selectedEmployees = [];

    #[Computed]
    public function activeSupervisors(): Collection
    {
        return $this
            ->getSupervisors(true);
    }

    #[Computed]
    public function inactiveSupervisors(): Collection
    {
        return $this->getSupervisors(false);
    }

    #[Computed]
    public function employeesWithoutSupervisor(): Collection
    {
        return Cache::rememberForever('employees_without_supervisor_list', function () {
            return Employee::query()
                ->whereNull('supervisor_id')
                ->active()
                ->get()
                ->split(2);
        });
    }

    public function deactivateSupervisorAction(): Action
    {
        return Action::make('deactivateSupervisor')
            ->label('Deactivate')
            ->color('danger')
            ->link()
            ->icon(Heroicon::OutlinedArrowRight)
            ->requiresConfirmation()
            ->modalHeading('Deactivate Supervisor')
            ->modalDescription('Are you sure you want to deactivate this supervisor? ')
            ->action(function (array $arguments): void {
                $supervisor = Supervisor::query()
                    ->withoutGlobalScopes([
                        IsActiveScope::class,
                    ])
                    ->findOrFail($arguments['supervisor']);

                if ($supervisor) {
                    $supervisor->is_active = false;
                    $supervisor->save();
                }
            });
    }

    public function reactivateSupervisorAction(): Action
    {
        return Action::make('reactivateSupervisor')
            ->label('Reactivate')
            ->color('success')
            ->link()
            ->icon(Heroicon::OutlinedArrowRight)
            ->requiresConfirmation()
            ->modalHeading('Reactivate Supervisor')
            ->modalDescription('Are you sure you want to reactivate this supervisor? ')
            ->action(function (array $arguments): void {
                $supervisor = Supervisor::query()
                    ->withoutGlobalScopes([
                        IsActiveScope::class,
                    ])
                    ->findOrFail($arguments['supervisor']);

                if ($supervisor) {
                    $supervisor->is_active = true;
                    $supervisor->save();
                }
            });
    }

    public function reasignSelectedEmployeesAction(): Action
    {
        return Action::make('reasignSelectedEmployees')
            ->label('Reasign Selected Employees')
            ->schema([
                Select::make('destination_supervisor_id')
                    ->label('Select Destination Supervisor')
                    ->options(ModelListService::make(
                        Supervisor::query()->where('is_active', true))
                    )
                    ->searchable()
                    ->required(),
            ])
            ->action(function (array $data): void {
                foreach ($this->selectedEmployees as $employeeId) {
                    $employee = Employee::find($employeeId);
                    if ($employee) {
                        $employee->supervisor_id = $data['destination_supervisor_id'];
                        $employee->save();
                    }
                }

                $this->selectedEmployees = [];
            });
    }

    public function selectAllForSupervisor($supervisor): void
    {
        $employeeIds = Supervisor::query()
            ->withoutGlobalScopes([
                IsActiveScope::class,
            ])
            ->findOrFail($supervisor)
            ->employees
            ?->pluck('id')
            ?->toArray() ?? [];
        // $selectedEmployeeIds = array_map('intval', $this->selectedEmployees);

        // Add all employees from this supervisor
        $this->selectedEmployees = array_unique(array_merge($this->selectedEmployees, $employeeIds));
        $this->selectedEmployees = array_values($this->selectedEmployees);
    }

    protected function getSupervisors(bool $isActive): Collection
    {
        return Cache::rememberForever('supervisors_with_'.($isActive ? 'active' : 'inactive'), function () use ($isActive) {
            return Supervisor::query()
                ->withoutGlobalScopes([
                    IsActiveScope::class,
                ])
                ->with(['employees' => function ($query) {
                    $query
                        ->active()
                        ->with(['position', 'site'])
                        ->orderBy('full_name');
                }])
                ->where('is_active', $isActive)
                ->orderBy('name')
                ->get();

        });
    }
}
