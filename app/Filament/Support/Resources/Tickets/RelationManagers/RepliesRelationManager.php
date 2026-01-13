<?php

namespace App\Filament\Support\Resources\Tickets\RelationManagers;

use App\Enums\TicketStatuses;
use App\Models\TicketReply;
use App\Services\ModelListService;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class RepliesRelationManager extends RelationManager
{
    protected static string $relationship = 'replies';

    protected $listeners = [
        'refreshRelationManagers' => '$refresh',
    ];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('content')
                    ->required()
                    ->minLength(5)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->poll('300s')
            ->recordTitleAttribute('iticket.reference')
            ->columns([
                TextColumn::make('user.name'),
                TextColumn::make('created_at')
                    ->wrap()
                    ->dateTime()
                    ->formatStateUsing(fn ($state) => $state->diffForHumans())
                    ->sortable(),
                TextColumn::make('content')
                    ->wrap()
                    ->extraAttributes(function (TicketReply $record) {
                        return $record->user_id === Auth::id() ?
                            [
                                'class' => 'text-gray-600 dark:text-white',
                            ] :
                            [
                                'style' => 'font-style: italic; margin-left: 2rem; font-weight: bold;',
                                'class' => 'text-black',
                            ];
                    }),
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
                // SelectFilter::make('user_id')
                //     ->options(function (RelationManager $livewire) {
                //         $ticket = $livewire->getOwnerRecord();

                //         return ModelListService::make(
                //                 model: TicketReply::query()
                //                     ->with('author')
                //                     ->where('ticket_id', $ticket->id)
                //                     ->get(),
                //                 value_field: 'author.name'
                //             );
                //     })

            ])
            ->headerActions([
                CreateAction::make()
                    ->visible(function (RelationManager $livewire) {
                        $ticket = $livewire->getOwnerRecord();
                        $auth_user = Auth::user();

                        return $ticket->status === TicketStatuses::CompletedExpired || $ticket->status === TicketStatuses::Completed ?
                            false :
                             $ticket->owner_id === $auth_user->id ||
                            $ticket->assigned_to === $auth_user->id ||
                            $auth_user->isTicketsAdmin();
                    })
                    ->using(function (array $data, string $model, RelationManager $livewire): TicketReply {
                        $ticket = $livewire->getOwnerRecord();
                        $data['user_id'] = Auth::id();
                        $data['ticket_id'] = $ticket->id;

                        return $model::create($data);
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn (TicketReply $record) => $record->user_id === Auth::id()),
                // DeleteAction::make()
                //     ->visible(fn (TicketReply $record) => $record->user_id === Auth::id()),
            ])
            ->toolbarActions([
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
