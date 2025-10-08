<?php

namespace App\Filament\Support\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Support\Resources\TicketDepartmentResource\Pages\ListTicketDepartments;
use App\Filament\Support\Resources\TicketDepartmentResource\Pages\CreateTicketDepartment;
use App\Filament\Support\Resources\TicketDepartmentResource\Pages\ViewTicketDepartment;
use App\Filament\Support\Resources\TicketDepartmentResource\Pages\EditTicketDepartment;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\TicketDepartment;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Support\Resources\TicketDepartmentResource\Pages;
use App\Filament\Support\Resources\TicketDepartmentResource\RelationManagers;

class TicketDepartmentResource extends Resource
{
    protected static ?string $model = TicketDepartment::class;

    protected static string | \BackedEnum | null $navigationIcon =  'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->minLength(3)
                            ->maxLength(500)
                            ->unique(ignoreRecord: true),
                        TextInput::make('ticket_prefix')
                            ->visibleOn('view')
                            ->maxLength(8),
                        Textarea::make('description')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('ticket_prefix')
                    ->searchable(),
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
            'index' => ListTicketDepartments::route('/'),
            'create' => CreateTicketDepartment::route('/create'),
            'view' => ViewTicketDepartment::route('/{record}'),
            'edit' => EditTicketDepartment::route('/{record}/edit'),
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
