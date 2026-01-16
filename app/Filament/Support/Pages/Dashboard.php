<?php

namespace App\Filament\Support\Pages;

use App\Filament\Support\Resources\MyTickets\MyTicketResource;
use App\Filament\Support\Widgets\TicketsCompletedTable;
use App\Filament\Support\Widgets\TicketsPendingTable;
use App\Filament\Support\Widgets\TicketsStatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('manageTickets');
    }

    public function mount()
    {
        if (! Auth::user()->can('manageTickets')) {

            $ticketsUrl = MyTicketResource::getUrl('index');

            return \redirect($ticketsUrl);
        }
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
