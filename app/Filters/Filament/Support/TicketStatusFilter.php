<?php

namespace App\Filters\Filament\Support;

use App\Enums\TicketStatuses;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Cache;

class TicketStatusFilter
{
    public static function make(): SelectFilter
    {
        return SelectFilter::make('status')
            ->label(__('Status'))
            ->options(
                Cache::rememberForever('ticket_statuses_compact_list', function () {
                    return TicketStatuses::toArray();
                })
            );
    }
}
