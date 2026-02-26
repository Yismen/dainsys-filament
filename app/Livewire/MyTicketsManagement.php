<?php

namespace App\Livewire;

use App\Actions\Filament\AssignTicketAction;
use App\Actions\Filament\CloseTicketAction;
use App\Actions\Filament\ReopenTicketAction;
use App\Enums\TicketPriorities;
use App\Enums\TicketStatuses;
use App\Models\Ticket;
use App\Schemas\Filament\Support\TicketSchema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.landing-page')]
class MyTicketsManagement extends Page implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Ticket::query()
                    ->where('owner_id', Auth::id())
                    // ->orwhere('assigned_to', Auth::id())
                    ->with(['owner', 'agent'])
            )
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('reference')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->wrap()
                    ->sortable(),
                TextColumn::make('subject')
                    ->wrap()
                    ->limit(50)
                    ->tooltip(fn (string $state) => $state)
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => $state->color() ?? TicketStatuses::from($state)->color())
                    ->sortable()
                    ->searchable(),
                TextColumn::make('agent.name')
                    ->label('Assigned to')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('assigned_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('completed_at')
                    ->wrap()
                    ->dateTime()
                    ->sortable(),
                ImageColumn::make('images')
                    ->disk('public')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText(),
                TextColumn::make('priority')
                    ->badge()
                    ->searchable(),
                TextColumn::make('expected_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
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
            ->headerActions([
                CreateAction::make()
                    ->schema(TicketSchema::make()),
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalFooterActions([
                        CloseTicketAction::make(),
                        ReopenTicketAction::make(),
                        AssignTicketAction::make(),

                    ])
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('reference'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn ($state) => $state->color() ?? TicketStatuses::from($state)->color()),
                                TextEntry::make('priority')
                                    ->badge(),
                                TextEntry::make('created_at')
                                    ->label(__('Created by'))
                                    ->formatStateUsing(fn ($record) => $record->owner->name.', at '.$record->created_at),
                                TextEntry::make('subject')
                                    ->label('Subject'),
                                TextEntry::make('description')
                                    ->label('Description'),
                                TextEntry::make('agent.name')
                                    ->label('Assigned to'),
                                TextEntry::make('assigned_at')
                                    ->dateTime(),
                                TextEntry::make('completed_at')
                                    ->wrap()
                                    ->dateTime(),
                                TextEntry::make('expected_at')
                                    ->dateTime(),
                                TextEntry::make('updated_at')
                                    ->dateTime(),
                            ]),
                    ]),
                EditAction::make()
                    ->schema(TicketSchema::make())
                    ->modalFooterActions([
                        CloseTicketAction::make(),
                        ReopenTicketAction::make(),
                        AssignTicketAction::make(),
                    ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('reference'),
                TextEntry::make('status')
                    ->badge()
                    ->color(fn ($state) => $state->color() ?? TicketStatuses::from($state)->color()),
                TextInput::make('subject')
                    ->autofocus()
                    ->minLength(5)
                    ->maxLength(150)
                    ->required(),
                Select::make('priority')
                    ->options(TicketPriorities::class)
                    ->default(TicketPriorities::Normal)
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('images')
                    ->visibility('public')
                    ->disk('public')
                    ->directory('tickets')
                    ->multiple()
                    ->image()
                    ->imageEditor()
                    ->maxFiles(5)
                    ->maxSize(1024)
                    ->columns(6)
                    ->imageEditorMode(2)
                    ->reorderable()
                    ->circleCropper()
                    ->columnSpanFull()
                    ->panelLayout('grid'),
            ]);
    }

    public function render(): View
    {
        return view('livewire.my-tickets-management');
    }
}
