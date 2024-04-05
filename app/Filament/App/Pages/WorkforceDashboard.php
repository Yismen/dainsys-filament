<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Filament\Pages\Dashboard;

class WorkforceDashboard extends Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // protected static string $view = 'filament.app.pages.workforce-dashboard';
    protected static string $routePath = 'workforce';
    protected static ?string $title = 'Workforce dashboard';
    protected static ?int $navigationSort = -1;
}
