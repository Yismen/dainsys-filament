<?php

namespace App\Providers\Filament;

use App\Services\FilamentPanelsService;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class BlogPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return FilamentPanelsService::make($panel)
            ->id('blog')
            ->path('blog-admin')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Blog/Resources'), for: 'App\\Filament\\Blog\\Resources')
            ->discoverPages(in: app_path('Filament/Blog/Pages'), for: 'App\\Filament\\Blog\\Pages')
            ->discoverWidgets(in: app_path('Filament/Blog/Widgets'), for: 'App\\Filament\\Blog\\Widgets');
    }
}
