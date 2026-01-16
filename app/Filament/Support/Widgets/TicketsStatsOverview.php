<?php

namespace App\Filament\Support\Widgets;

use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TicketsStatsOverview extends StatsOverviewWidget
{
    public static function canView(): bool
    {
        return Auth::user()->isSuperAdmin() || Auth::user()->isTicketsManager() || Auth::user()->isTicketsAgent();
    }

    protected function getStats(): array
    {
        $total_tickets = Cache::rememberForever('total_tickets', fn () => Ticket::count());
        $total_tickets_in_progress = Cache::rememberForever('total_tickets_in_progress', fn () => Ticket::inProgress()->count());
        $total_tickets_pending = Cache::rememberForever('total_tickets_pending', fn () => Ticket::pending()->count());
        $total_tickets_completed = Cache::rememberForever('total_tickets_completed', fn () => Ticket::completed()->count());
        $total_tickets_completed_percentage = $total_tickets ? \number_format($total_tickets_completed / $total_tickets * 100) : 0;
        $total_tickets_to_work = $total_tickets_pending + $total_tickets_in_progress;
        $total_tickets_compliant = Cache::rememberForever('total_tickets_compliant', fn () => Ticket::compliant()->count());
        $compliant_percentage = $total_tickets_completed ? \number_format($total_tickets_compliant / $total_tickets_completed * 100) : 0;
        $in_progress_percentage = $total_tickets_to_work ? \number_format($total_tickets_in_progress / $total_tickets_to_work * 100) : 0;
        $pending_percentage = $total_tickets_to_work ? \number_format($total_tickets_pending / $total_tickets_to_work * 100) : 0;

        return [
            Stat::make('Tickets Created', $total_tickets)
                ->color('info'),
            Stat::make('Tickets Completed', $total_tickets_completed)
                ->color($total_tickets_completed_percentage < 80 ? 'danger' : 'info')
                ->description($total_tickets_completed_percentage.'%'),
            Stat::make('Compliance %', $total_tickets_compliant)
                ->color($compliant_percentage < 80 ? 'danger' : 'info')
                ->description($compliant_percentage.'%'),
            Stat::make('Tickets In Progress', $total_tickets_in_progress)
                ->color('info')
                ->description($in_progress_percentage.'%'),
            Stat::make('Tickets Pending', $total_tickets_pending)
                ->color('warning')
                ->description($pending_percentage.'%'),
        ];
    }
}
