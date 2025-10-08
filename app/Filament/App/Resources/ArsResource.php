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
use App\Filament\App\Resources\ArsResource\Pages\ListArs;
use App\Filament\App\Resources\ArsResource\Pages\CreateArs;
use App\Filament\App\Resources\ArsResource\Pages\ViewArs;
use App\Filament\App\Resources\ArsResource\Pages\EditArs;
use App\Models\Ars;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Support\Forms\ArsSchema;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Traits\HumanResourceAdminMenu;
use App\Filament\App\Resources\ArsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\ArsResource\RelationManagers;
use App\Filament\App\Resources\InformationResource\RelationManagers\InformationRelationManager;

class ArsResource extends Resource
{
    use HumanResourceAdminMenu;
    protected static ?string $model = Ars::class;

    protected static string | \BackedEnum | null $navigationIcon =  'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->schema(ArsSchema::toArray())
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
            InformationRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListArs::route('/'),
            'create' => CreateArs::route('/create'),
            'view' => ViewArs::route('/{record}'),
            'edit' => EditArs::route('/{record}/edit'),
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
