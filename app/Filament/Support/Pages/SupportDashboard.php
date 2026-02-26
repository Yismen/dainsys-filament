<?php

namespace App\Filament\Support\Pages;

use App\Filament\Support\Widgets\TicketsCompletedTable;
use App\Filament\Support\Widgets\TicketsPendingTable;
use App\Filament\Support\Widgets\TicketsStatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;

class SupportDashboard extends BaseDashboard
{
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('manageTickets');
    }

    public function getWidgets(): array
    {
        return [
            TicketsStatsOverview::class,
            TicketsPendingTable::class,
            TicketsCompletedTable::class,
        ];
    }
}
