<?php

namespace App\Filament\Support\Pages;

use App\Filament\Support\Widgets\TicketsCompletedTable;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Support\Widgets\TicketsStatsOverview;
use App\Filament\Support\Widgets\TicketsPendingTable;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            TicketsStatsOverview::class,
            TicketsPendingTable::class,
            TicketsCompletedTable::class,
        ];
    }
}
