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
            HRActivityRequestStats::class,
            UpcomingEmployeeBirthdays::class,
            HeadCountBySite::class,
            HeadCountByProject::class,
            HeadCountByPosition::class,
            HeadCountBySupervisor::class,
        ];
    }

    public function persistsFiltersInSession(): bool
    {
        return false;
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make()
                    ->schema([

                        Select::make('site')
                            ->searchable()
                            ->multiple()
                            ->default(fn () => config('app.default_sites', []))
                            ->options(ModelListService::make(Site::query())),
                        Select::make('project')
                            ->searchable()
                            ->multiple()
                            ->options(ModelListService::make(Project::query())),
                        Select::make('supervisor')
                            ->searchable()
                            ->multiple()
                            ->options(ModelListService::make(Supervisor::query())),
                    ]),
            ]);
    }
}
