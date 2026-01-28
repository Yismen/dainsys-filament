<?php

namespace App\Filament\HumanResource\Pages;

use App\Filament\HumanResource\Widgets\EmployeesStats;
use App\Filament\HumanResource\Widgets\HeadCountByPosition;
use App\Filament\HumanResource\Widgets\HeadCountByProject;
use App\Filament\HumanResource\Widgets\HeadCountBySite;
use App\Filament\HumanResource\Widgets\HeadCountBySupervisor;
use App\Filament\HumanResource\Widgets\HRActivityRequestStats;
use App\Filament\HumanResource\Widgets\UpcomingEmployeeBirthdays;
use App\Models\Project;
use App\Models\Site;
use App\Models\Supervisor;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HumanResourceDashboard extends Dashboard
{
    use HasFiltersForm;

    #[\Livewire\Attributes\On('livewire:init')]
    public function applyDefaultFilters(): void
    {
        $defaultSites = config('app.default_sites', []);

        if (! empty($defaultSites) && empty($this->filters['site'] ?? null)) {
            $this->filters['site'] = $defaultSites;
        }
    }

    public function getWidgets(): array
    {
        return [
            EmployeesStats::class,
            HeadCountBySite::class,
            HeadCountByProject::class,
            HeadCountByPosition::class,
            HeadCountBySupervisor::class,
            HRActivityRequestStats::class,
            UpcomingEmployeeBirthdays::class,
        ];
    }

    public function persistsFiltersInSession(): bool
    {
        return false;
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'md' => 1,
                'xl' => 1,
                '2xl' => 1,
            ])
            ->components([
                Section::make()
                    ->columns(3)
                    ->schema([

                        Select::make('site')
                            ->searchable()
                            ->multiple()
                            ->default(fn () => config('app.default_sites', []))
                            ->options(ModelListService::make(Site::query()->whereHas('employees', fn ($query) => $query->notInactive()))),
                        Select::make('project')
                            ->searchable()
                            ->multiple()
                            ->options(ModelListService::make(Project::query()->whereHas('employees', fn ($query) => $query->notInactive()))),
                        Select::make('supervisor')
                            ->searchable()
                            ->multiple()
                            ->options(ModelListService::make(Supervisor::query()->whereHas('employees', fn ($query) => $query->notInactive()))),
                    ]),
            ]);
    }
}
