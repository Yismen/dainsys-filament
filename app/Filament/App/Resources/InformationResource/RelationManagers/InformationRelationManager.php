<?php

namespace App\Filament\App\Resources\InformationResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class InformationRelationManager extends RelationManager
{
    protected static string $relationship = 'information';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->columns(2)
                    ->schema([
                        TextInput::make('phone')
                            ->required()
                            ->minLength(10)
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->minLength(5)
                            ->maxLength(255),
                        TextInput::make('address')
                            ->required()
                            ->minLength(5)
                            ->maxLength(255),
                        TextInput::make('company_id')
                            ->minLength(2)
                            ->maxLength(255),
                        FileUpload::make('photo_url')
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
                ImageColumn::make('photo_url')
                    ->circular(),
                TextColumn::make('phone'),
                TextColumn::make('email'),
                TextColumn::make('address'),
                TextColumn::make('company_id'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
