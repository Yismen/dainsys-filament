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
use App\Filament\App\Resources\SuspensionTypeResource\Pages\ListSuspensionTypes;
use App\Filament\App\Resources\SuspensionTypeResource\Pages\CreateSuspensionType;
use App\Filament\App\Resources\SuspensionTypeResource\Pages\ViewSuspensionType;
use App\Filament\App\Resources\SuspensionTypeResource\Pages\EditSuspensionType;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\SuspensionType;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Traits\HumanResourceAdminMenu;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Support\Forms\SuspensionTypeSchema;
use App\Filament\App\Resources\SuspensionTypeResource\Pages;
use App\Filament\App\Resources\SuspensionTypeResource\RelationManagers;

class SuspensionTypeResource extends Resource
{
    use HumanResourceAdminMenu;

    protected static ?string $model = SuspensionType::class;

    protected static string | \BackedEnum | null $navigationIcon =  'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->schema(SuspensionTypeSchema::toArray())
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
            'index' => ListSuspensionTypes::route('/'),
            'create' => CreateSuspensionType::route('/create'),
            'view' => ViewSuspensionType::route('/{record}'),
            'edit' => EditSuspensionType::route('/{record}/edit'),
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
