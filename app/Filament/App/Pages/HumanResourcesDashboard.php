<?php

namespace App\Filament\App\Pages;

use Filament\Forms\Form;
use App\Services\SiteService;
use Filament\Pages\Dashboard;
use Filament\Forms\Components\Section;
use App\Filament\Traits\HumanResourceAdminMenu;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class HumanResourcesDashboard extends Dashboard
{
    use HumanResourceAdminMenu;
    use HasFiltersForm;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // protected static string $view = 'filament.app.pages.human-resources-dashboard';
    protected static string $routePath = 'human-resource';
    protected static ?string $title = 'Dashboard';
    protected static ?int $navigationSort = -1;

    public function getWidgets(): array
    {
        return [
            \App\Filament\App\Widgets\HumanResource\EmployeesStats::class,
            \App\Filament\App\Widgets\HumanResource\HeadCountBySite::class,
            \App\Filament\App\Widgets\HumanResource\HeadCountByDepartment::class,
            \App\Filament\App\Widgets\HumanResource\HeadCountByProject::class,
            \App\Filament\App\Widgets\HumanResource\HeadCountByPosition::class,
            \App\Filament\App\Widgets\HumanResource\HeadCountByAfp::class,
            \App\Filament\App\Widgets\HumanResource\HeadCountByArs::class,
            // \App\Filament\App\Widgets\HumanResource\MonthlyEmployees::class,
            // \App\Filament\App\Widgets\HumanResource\MonthlyAttrition::class,
        ];
    }

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        \Filament\Forms\Components\Select::make('site')
                            ->options(SiteService::list())
                        // ...
                    ])
                    ->columns(3),
            ]);
    }

    public function getHeaderWidgetsColumns(): int | string | array
    {
        return 4;
    }
}
