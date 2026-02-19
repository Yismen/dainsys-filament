<?php

namespace App\Filament\Workforce\Pages;

use App\Models\Employee;
use App\Models\Scopes\IsActiveScope;
use App\Models\Supervisor;
use App\Services\ModelListService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use UnitEnum;

class ManageSupervisors extends Page
{
    use InteractsWithActions;

    protected string $view = 'filament.workforce.pages.manage-supervisors';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBattery50;

    protected static ?int $navigationSort = 7;

    public array $selectedEmployees = [];

    protected static string|UnitEnum|null $navigationGroup = 'Management';

    #[On('employeesSelected')]
    public function employeesSelected($employees): void
    {
        // go over the employees array and map to selectedEmployees, removing or adding ids depending on selected value
        \array_walk($employees, function ($employee): void {
            if ($employee['selected']) {
                if (! in_array($employee['id'], $this->selectedEmployees)) {
                    $this->selectedEmployees[] = $employee['id'];
                }
            } else {
                if (in_array($employee['id'], $this->selectedEmployees)) {
                    $this->selectedEmployees = array_filter($this->selectedEmployees, function ($id) use ($employee) {
                        return $id !== $employee['id'];
                    });
                }
            }
        });
    }

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

                $employees = Employee::query()
                    ->whereIn('id', $this->selectedEmployees)
                    ->get();
                $supervisor = Supervisor::find($data['destination_supervisor_id']);

                Notification::make()
                    ->title('Employees Reassigned')
                    ->body('The employees '.\implode(', ', $employees->pluck('full_name')->toArray()).' have been reassigned to you.')
                    ->sendToDatabase($supervisor->user ?? auth()->user());

                Cache::forget('supervisors_with_active');
                Cache::forget('supervisors_with_inactive');
                Cache::forget('employees_without_supervisor_list');

                // Unset computed properties to force re-evaluation
                unset($this->activeSupervisors);
                unset($this->inactiveSupervisors);
                unset($this->employeesWithoutSupervisor);

                $this->selectedEmployees = [];

                $this->dispatch('employeesReassigned');

                $this->redirect(request()->header('referer'), navigate: true);

            });
    }

    protected function getSupervisors(bool $isActive): Collection
    {
        return Cache::rememberForever('supervisors_with_'.($isActive ? 'active' : 'inactive'), function () use ($isActive) {
            return Supervisor::query()
                ->withoutGlobalScopes([
                    IsActiveScope::class,
                ])
                ->with(['employees' => function ($query): void {
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
