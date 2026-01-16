<?php

namespace App\Filament\Support\Resources\MyTickets\Schemas;

use App\Enums\TicketPriorities;
use App\Enums\TicketStatuses;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MyTicketForm
{
    public static function configure(Schema $schema): Schema
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
}
