<?php

namespace App\Filament\Support\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Support\Resources\TicketResource\Pages\ListTickets;
use App\Filament\Support\Resources\TicketResource\Pages\CreateTicket;
use App\Filament\Support\Resources\TicketResource\Pages\ViewTicket;
use App\Filament\Support\Resources\TicketResource\Pages\EditTicket;
use Filament\Forms;
use Filament\Tables;
use App\Models\Ticket;
use Filament\Tables\Table;
use App\Enums\TicketStatuses;
use App\Enums\TicketPriorities;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Support\Resources\TicketResource\Pages;
use App\Filament\Support\Resources\TicketResource\RelationManagers;
use App\Filament\Support\Resources\TicketResource\RelationManagers\RepliesRelationManager;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static string | \BackedEnum | null $navigationIcon =  'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->schema([
                        // Forms\Components\Select::make('owner_id')
                        //     ->relationship('owner', 'name')
                        //     ->required(),
                        TextInput::make('reference')
                            ->visibleOn('view')
                            ->maxLength(50),
                        Select::make('department_id')
                            ->relationship('department', 'name')
                            ->required(),
                        TextInput::make('subject')
                            ->required()
                            ->maxLength(300),
                        Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                        FileUpload::make('images')
                            ->image()
                            ->imageEditor()
                            ->multiple()
                            ->columnSpanFull(),
                        Select::make('priority')
                            ->options(TicketPriorities::class)
                            ->required()
                            ->default(TicketPriorities::Normal),
                        TextInput::make('status')
                            ->formatStateUsing(fn($state) => TicketStatuses::from($state)->name)
                            ->required()
                            ->visibleOn('view'),
                        DateTimePicker::make('completed_at')
                            ->visibleOn('view'),
                        DateTimePicker::make('expected_at')
                            ->visibleOn('view'),
                        TextInput::make('assigned_to')
                            ->numeric()
                            ->visibleOn('view'),
                        DateTimePicker::make('assigned_at')
                            ->visibleOn('view'),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('owner.name')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('reference')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('department.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('subject')
                    ->searchable(),
                ImageColumn::make('images')
                    ->stacked()
                    ->circular()
                    ->limit(3)
                    ->limitedRemainingText()
                    ->extraImgAttributes(['loading' => 'lazy']),
                TextColumn::make('status')
                    ->formatStateUsing(fn($state) => $state->name)
                    ->searchable(),
                TextColumn::make('assigned_to')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('assigned_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('priority')
                    ->formatStateUsing(fn($state) => $state->name)
                    ->searchable(),
                TextColumn::make('expected_at')
                    ->dateTime()
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
                SelectFilter::make('status')
                    ->multiple()
                    ->options(TicketStatuses::toArray())
                    ->default(TicketStatuses::Pending->value),
            ], layout: FiltersLayout::AboveContentCollapsible)
            // ->filtersFormColumns(3)
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
            RepliesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTickets::route('/'),
            'create' => CreateTicket::route('/create'),
            'view' => ViewTicket::route('/{record}'),
            'edit' => EditTicket::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('owner_id', auth()->user()->id)
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
