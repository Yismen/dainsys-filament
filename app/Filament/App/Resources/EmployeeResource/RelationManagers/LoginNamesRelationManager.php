<?php

namespace App\Filament\App\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class LoginNamesRelationManager extends RelationManager
{
    protected static string $relationship = 'loginNames';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('login_name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(1000),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('login_name')
            ->columns([
                Tables\Columns\TextColumn::make('login_name'),
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
