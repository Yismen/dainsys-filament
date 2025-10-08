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
use App\Filament\App\Resources\SuspensionResource\Pages\ListSuspensions;
use App\Filament\App\Resources\SuspensionResource\Pages\CreateSuspension;
use App\Filament\App\Resources\SuspensionResource\Pages\ViewSuspension;
use App\Filament\App\Resources\SuspensionResource\Pages\EditSuspension;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use App\Models\Suspension;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Support\Forms\EmployeeSchema;
use App\Filament\Support\Forms\SuspensionSchema;
use App\Filament\Traits\HumanResourceSupportMenu;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Support\Forms\SuspensionTypeSchema;
use App\Filament\App\Resources\SuspensionResource\Pages;
use App\Filament\App\Resources\SuspensionResource\RelationManagers;

class SuspensionResource extends Resource
{
    use HumanResourceSupportMenu;

    protected static ?string $model = Suspension::class;

    protected static string | \BackedEnum | null $navigationIcon =  'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->columns(2)
                    ->schema(SuspensionSchema::toArray())
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.full_name')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('suspensionType.name')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('starts_at')
                    ->date()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->date()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => ListSuspensions::route('/'),
            'create' => CreateSuspension::route('/create'),
            'view' => ViewSuspension::route('/{record}'),
            'edit' => EditSuspension::route('/{record}/edit'),
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
