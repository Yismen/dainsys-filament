<?php

namespace App\Filament\Support\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Ticket;
use Filament\Forms\Form;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('')
                    ->schema([
                        // Forms\Components\Select::make('owner_id')
                        //     ->relationship('owner', 'name')
                        //     ->required(),
                        Forms\Components\TextInput::make('reference')
                            ->visibleOn('view')
                            ->maxLength(50),
                        Forms\Components\Select::make('department_id')
                            ->relationship('department', 'name')
                            ->required(),
                        Forms\Components\TextInput::make('subject')
                            ->required()
                            ->maxLength(300),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('images')
                            ->image()
                            ->imageEditor()
                            ->multiple()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('priority')
                            ->options(TicketPriorities::class)
                            ->required()
                            ->default(TicketPriorities::Normal),
                        Forms\Components\TextInput::make('status')
                            ->formatStateUsing(fn($state) => TicketStatuses::from($state)->name)
                            ->required()
                            ->visibleOn('view'),
                        Forms\Components\DateTimePicker::make('completed_at')
                            ->visibleOn('view'),
                        Forms\Components\DateTimePicker::make('expected_at')
                            ->visibleOn('view'),
                        Forms\Components\TextInput::make('assigned_to')
                            ->numeric()
                            ->visibleOn('view'),
                        Forms\Components\DateTimePicker::make('assigned_at')
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
                Tables\Columns\TextColumn::make('reference')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('images')
                    ->stacked()
                    ->circular()
                    ->limit(3)
                    ->limitedRemainingText()
                    ->extraImgAttributes(['loading' => 'lazy']),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn($state) => $state->name)
                    ->searchable(),
                Tables\Columns\TextColumn::make('assigned_to')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assigned_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->formatStateUsing(fn($state) => $state->name)
                    ->searchable(),
                Tables\Columns\TextColumn::make('expected_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('status')
                    ->multiple()
                    ->options(TicketStatuses::toArray())
                    ->default(TicketStatuses::Pending->value),
            ], layout: \Filament\Tables\Enums\FiltersLayout::AboveContentCollapsible)
            // ->filtersFormColumns(3)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'view' => Pages\ViewTicket::route('/{record}'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
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
