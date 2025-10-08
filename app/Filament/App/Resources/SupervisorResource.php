<?php

namespace App\Filament\App\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\App\Resources\SupervisorResource\Pages\ListSupervisors;
use App\Filament\App\Resources\SupervisorResource\Pages\CreateSupervisor;
use App\Filament\App\Resources\SupervisorResource\Pages\ViewSupervisor;
use App\Filament\App\Resources\SupervisorResource\Pages\EditSupervisor;
use Filament\Forms;
use Filament\Tables;
use App\Models\Supervisor;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Traits\HumanResourceAdminMenu;
use App\Filament\Support\Forms\SupervisorSchema;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\SupervisorResource\Pages;
use App\Filament\App\Resources\SupervisorResource\RelationManagers;

class SupervisorResource extends Resource
{
    use HumanResourceAdminMenu;

    protected static ?string $model = Supervisor::class;

    protected static string | \BackedEnum | null $navigationIcon =  'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->schema(SupervisorSchema::toArray())
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('description')
                    ->limit(),
                // Tables\Columns\TextColumn::make('deleted_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSupervisors::route('/'),
            'create' => CreateSupervisor::route('/create'),
            'view' => ViewSupervisor::route('/{record}'),
            'edit' => EditSupervisor::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
