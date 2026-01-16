<?php

namespace App\Filament\Support\Resources\Tickets;

use App\Filament\Support\Resources\Tickets\Pages\CreateTicket;
use App\Filament\Support\Resources\Tickets\Pages\EditTicket;
use App\Filament\Support\Resources\Tickets\Pages\ListTickets;
use App\Filament\Support\Resources\Tickets\Pages\ViewTicket;
use App\Filament\Support\Resources\Tickets\RelationManagers\RepliesRelationManager;
use App\Filament\Support\Resources\Tickets\Schemas\TicketInfolist;
use App\Models\Ticket;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'reference';

    protected static bool $shouldRegisterNavigation = false;

    public static function canAccess(): bool
    {
        return Auth::user()->isSuperAdmin() || Auth::user()->isTicketsAdmin() || Auth::user()->isTicketsOperator();
    }

    public static function infolist(Schema $schema): Schema
    {
        return TicketInfolist::configure($schema);
    }

    public static function getRelations(): array
    {
        return [
            RepliesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTickets::route('/'),
            // 'create' => CreateTicket::route('/create'),
            'view' => ViewTicket::route('/{record}'),
            // 'edit' => EditTicket::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        // ->where('owner_id', Auth::id())
        // ->orwhere('assigned_to', Auth::id())

        return $query;
    }
}
