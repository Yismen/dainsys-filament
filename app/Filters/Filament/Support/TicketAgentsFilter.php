<?php

namespace App\Filters\Filament\Support;

use App\Models\Ticket;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Cache;

class TicketAgentsFilter
{
    public static function make(): SelectFilter
    {
        return SelectFilter::make('assigned_to')
            ->label(__('Agent'))
            ->options(
                Cache::rememberForever('ticket_agents_compactasdfasdfadsfadsfadsf_list', function () {
                    return Ticket::query()
                        ->select('assigned_to')
                        ->distinct()
                        ->withWhereHas('agent:id,name')
                        ->get()
                        ->pluck('agent.name', 'assigned_to')
                        ->toArray();
                })
            );
    }
}
