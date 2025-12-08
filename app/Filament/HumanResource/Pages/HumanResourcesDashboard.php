<?php

namespace App\Filament\HumanResource\Pages;

use BackedEnum;
use App\Filament\App\Widgets\HumanResource\EmployeesStats;
use App\Filament\App\Widgets\HumanResource\HeadCountBySite;
use App\Filament\App\Widgets\HumanResource\HeadCountByDepartment;
use App\Filament\App\Widgets\HumanResource\HeadCountByProject;
use App\Filament\App\Widgets\HumanResource\HeadCountByPosition;
use App\Filament\App\Widgets\HumanResource\HeadCountByAfp;
use App\Filament\App\Widgets\HumanResource\HeadCountByArs;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Actions\Action;
use Filament\Schemas\Components\Utilities\Set;
use App\Services\SiteService;
use Filament\Pages\Dashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class HumanResourcesDashboard extends Dashboard
{
    use HasFiltersForm;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    // protected static string $view = 'filament.app.pages.human-resources-dashboard';
    protected static string $routePath = 'human-resource';
    protected static ?string $title = 'Dashboard';
    protected static ?int $navigationSort = -1;

    public function getWidgets(): array
    {
        return [
            EmployeesStats::class,
            HeadCountBySite::class,
            HeadCountByDepartment::class,
            HeadCountByProject::class,
            HeadCountByPosition::class,
            HeadCountByAfp::class,
            HeadCountByArs::class,
            // \App\Filament\App\Widgets\HumanResource\MonthlyEmployees::class,
            // \App\Filament\App\Widgets\HumanResource\MonthlyAttrition::class,
        ];
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Select::make('site')
                            ->options(SiteService::list())
                            ->multiple()
                            ->live()
                            ->suffixAction(
                                Action::make('clear')
                                    ->icon('heroicon-m-x-mark')
                                    ->color('danger')
                                    ->visible(function ($state) {
                                        return $state;
                                    })
                                    ->action(function (Set $set) {
                                        $set('site', []);
                                    })
                            )
                    ])
                    ->columns(2),
                // ...

            ]);
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 4;
    }

    public function persistsFiltersInSession(): bool
    {
        return false;
    }
}
