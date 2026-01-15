<?php

namespace App\Filament\Support\Widgets;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use League\CommonMark\Extension\DescriptionList\Node\Description;

class TicketsStatsOverview extends StatsOverviewWidget
{
    public static function canView(): bool
    {
        return Auth::user()->isSuperAdmin() || Auth::user()->isTicketsAdmin() || Auth::user()->isTicketsOperator();
    }

    protected function getStats(): array
    {
        $total_tickets = Cache::rememberForever('total_tickets', fn() => Ticket::count());
        $total_tickets_in_progress = Cache::rememberForever('total_tickets_in_progress', fn() => Ticket::inProgress()->count());
        $total_tickets_pending = Cache::rememberForever('total_tickets_pending', fn() => Ticket::pending()->count());
        $total_tickets_completed = Cache::rememberForever('total_tickets_completed', fn() => Ticket::completed()->count());
        $total_tickets_completed_percentage = \number_format($total_tickets_completed / $total_tickets * 100 ?? 0);
        $total_tickets_to_work = $total_tickets_pending + $total_tickets_in_progress;
        $total_tickets_compliant = Cache::rememberForever('total_tickets_compliant', fn() => Ticket::compliant()->count());
        $compliant_percentage = \number_format($total_tickets_compliant / $total_tickets_completed * 100 ?? 0);

        return [
            Stat::make('Tickets Created', $total_tickets)
                ->color('info'),
            Stat::make('Tickets Completed', $total_tickets_completed)
                ->color($total_tickets_completed_percentage < 80 ? 'danger' : 'info')
                ->description($total_tickets_completed_percentage. "%"),
            Stat::make('Compliance %', $total_tickets_compliant)
                ->color($compliant_percentage < 80 ? 'danger' : 'info')
                ->description($compliant_percentage . "%"),
            Stat::make('Tickets In Progress', $total_tickets_in_progress)
                ->color('info')
                ->description(\number_format($total_tickets_in_progress / $total_tickets_to_work * 100 ?? 0) . "%"),
            Stat::make('Tickets Pending', $total_tickets_pending)
                ->color('warning')
                ->description(\number_format($total_tickets_pending / $total_tickets_to_work * 100 ?? 0) . "%"),
        ];
    }
}
