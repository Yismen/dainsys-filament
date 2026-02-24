<?php

namespace App\Providers\Filament;

use AchyutN\FilamentLogViewer\FilamentLogViewer;
use App\Services\FilamentPanelsService;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BinaryBuilds\FilamentFailedJobs\FilamentFailedJobsPlugin;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return FilamentPanelsService::make($panel)
            ->default()
            ->id('admin')
            ->path('admin')
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup('Access Control')
                    ->globallySearchable(true)
                    ->globalSearchResultsLimit(50)
                    ->forceGlobalSearchCaseInsensitive(true)
                    ->splitGlobalSearchTerms(false)
                    ->modelLabel('Model')
                    ->pluralModelLabel('Models')
                    ->recordTitleAttribute('name')
                    ->titleCaseModelLabel(true),
                FilamentLogViewer::make()
                    ->navigationGroup('System'),
                FilamentFailedJobsPlugin::make()
                    ->navigationGroup('System')
                    ->hideQueueOnIndex(),
                BreezyCore::make()
                    ->myProfile()
                    ->enableTwoFactorAuthentication()
                    ->enableSanctumTokens(permissions: ['use-dainsys']),
            ])
            ->navigationItems([
                NavigationItem::make()
                    ->group('System')
                    ->label('Pulse')
                    ->icon('heroicon-o-bolt')
                    ->url(fn (): string => route('pulse'))
                    ->openUrlInNewTab(),
                NavigationItem::make()
                    ->group('System')
                    ->label('Telescope')
                    ->icon('heroicon-o-cursor-arrow-ripple')
                    ->url(fn (): string => route('telescope'))
                    ->openUrlInNewTab(),
            ]);
    }
}
