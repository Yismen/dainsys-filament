<?php

namespace App\Filament\App\Resources\InformationResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class InformationRelationManager extends RelationManager
{
    protected static string $relationship = 'information';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->required()
                            ->minLength(10)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->minLength(5)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('address')
                            ->required()
                            ->minLength(5)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('company_id')
                            ->minLength(2)
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('photo_url')
                            ->directory('informations_photo')
                            ->image()
                            ->imageEditor()
                            ->preserveFilenames()
                            ->maxSize(2000),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('address')
            ->columns([
                Tables\Columns\ImageColumn::make('photo_url')
                    ->circular(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('address'),
                Tables\Columns\TextColumn::make('company_id'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
