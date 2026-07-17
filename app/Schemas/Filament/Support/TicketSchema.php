<?php

namespace App\Schemas\Filament\Support;

use App\Enums\TicketPriorities;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class TicketSchema
{
    public static function make(): array
    {
        return [
            TextInput::make('subject')
                ->label(__('filament.subject'))
                ->autofocus()
                ->minLength(5)
                ->maxLength(150)
                ->required(),
            Select::make('priority')
                ->label(__('filament.priority'))
                ->options(TicketPriorities::class)
                ->default(TicketPriorities::Normal)
                ->required(),
            RichEditor::make('description')
                ->label(__('filament.description'))
                ->required()
                ->columnSpanFull()
                ->extraInputAttributes(['class' => 'min-h-[200px]'])
                ->toolbarButtons([
                    'bold',
                    'italic',
                    'strike',
                    'h2',
                    'h3',
                    'orderedList',
                    'bulletList',
                    'link',
                    'blockquote',
                    'codeBlock',
                    'undo',
                    'redo',
                ]),
            FileUpload::make('images')
                ->label(__('filament.images'))
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
        ];
    }
}
