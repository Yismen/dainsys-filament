<?php

namespace App\Filters\Filament\Support;

use App\Models\Ticket;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Cache;

class TicketOwnersFilter
{
    public static function make(): SelectFilter
    {
        return SelectFilter::make('owner_id')
            ->label(__('Owner'))
            ->options(
                Cache::rememberForever('ticket_owners_compact_list', function () {
                    return Ticket::query()
                        ->select('owner_id')
                        ->distinct()
                        ->withWhereHas('owner:id,name')
                        ->get()
                        ->pluck('owner.name', 'owner_id')
                        ->toArray();
                })
            );
    }
}
