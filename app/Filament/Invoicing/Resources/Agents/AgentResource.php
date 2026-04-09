<?php

namespace App\Filament\Invoicing\Resources\Agents;

use App\Filament\Invoicing\Resources\Agents\Pages\ManageAgents;
use App\Filament\Invoicing\Resources\Agents\Schemas\AgentForm;
use App\Filament\Invoicing\Resources\Agents\Schemas\AgentInfolist;
use App\Filament\Invoicing\Resources\Agents\Tables\AgentsTable;
use App\Models\InvoiceAgent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AgentResource extends Resource
{
    protected static ?string $model = InvoiceAgent::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 11;

    protected static string|\UnitEnum|null $navigationGroup = 'Management';

    public static function form(Schema $schema): Schema
    {
        return AgentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AgentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AgentsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAgents::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
