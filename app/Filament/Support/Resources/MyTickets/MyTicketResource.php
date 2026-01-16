<?php

namespace App\Filament\Support\Resources\MyTickets;

use App\Filament\Support\Resources\MyTickets\Pages\CreateMyTicket;
use App\Filament\Support\Resources\MyTickets\Pages\EditMyTicket;
use App\Filament\Support\Resources\MyTickets\Pages\ListMyTickets;
use App\Filament\Support\Resources\MyTickets\Pages\ViewMyTicket;
use App\Filament\Support\Resources\MyTickets\Schemas\MyTicketForm;
use App\Filament\Support\Resources\MyTickets\Schemas\MyTicketInfolist;
use App\Filament\Support\Resources\MyTickets\Tables\MyTicketsTable;
use App\Filament\Support\Resources\Tickets\RelationManagers\RepliesRelationManager;
use App\Models\Ticket;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class MyTicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'reference';

    protected static ?string $modelLabel = 'MyTicket';

    protected static ?string $pluralModelLabel = 'MyTickets';

    public static function form(Schema $schema): Schema
    {
        return MyTicketForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MyTicketInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MyTicketsTable::configure($table);
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
            'index' => ListMyTickets::route('/'),
            'create' => CreateMyTicket::route('/create'),
            'view' => ViewMyTicket::route('/{record}'),
            'edit' => EditMyTicket::route('/{record}/edit'),
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
        $query = parent::getEloquentQuery()
            ->where('owner_id', Auth::id());
        // ->orwhere('assigned_to', Auth::id())

        return $query;
    }
}
