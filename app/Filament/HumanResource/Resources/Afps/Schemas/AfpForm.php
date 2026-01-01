<?php

namespace App\Filament\HumanResource\Resources\Afps\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

class AfpForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('AFP')
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true)
                                ->autofocus(),
                            TextInput::make('person_of_contact'),
                            Textarea::make('description')
                                ->columnSpanFull(),
                        ]),

                    Step::make('Information')
                        ->schema([
                            TextInput::make('information.phone')
                                ->required()
                                ->tel(),
                            TextInput::make('information.email')
                                ->email(),
                            Textarea::make('information.address'),
                        ])
                ])->submitAction(null)
            ]);
    }
}
