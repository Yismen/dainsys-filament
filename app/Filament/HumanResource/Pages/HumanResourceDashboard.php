<?php

namespace App\Filament\HumanResource\Pages;

use App\Models\Site;
use App\Models\Project;
use Filament\Pages\Page;
use App\Models\Supervisor;
use Filament\Schemas\Schema;
use Filament\Pages\Dashboard;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use App\Filament\HumanResource\Widgets\EmployeesStats;
use App\Filament\HumanResource\Widgets\HeadCountByPosition;
use App\Filament\HumanResource\Widgets\HeadCountByProject;
use App\Filament\HumanResource\Widgets\HeadCountBySite;
use App\Filament\HumanResource\Widgets\HeadCountBySupervisor;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\Width;

class HumanResourceDashboard extends Dashboard
{
    use HasFiltersAction;

    public function getWidgets(): array
    {
        return [
            EmployeesStats::class,
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

     protected function getHeaderActions(): array
    {
        return [
            FilterAction::make()
                ->slideOver(true)
                ->schema([
                    Select::make('site')
                        ->searchable()
                        ->multiple()
                        ->options(ModelListService::make(Site::query())),
                    Select::make('project')
                        ->searchable()
                        ->multiple()
                        ->options(ModelListService::make(Project::query())),
                    Select::make('supervisor')
                        ->searchable()
                        ->multiple()
                        ->options(ModelListService::make(Supervisor::query())),
                ])
        ];
    }
}
