<?php

namespace App\Filament\Resources\RoleResource\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RolesRelationManager extends RelationManager
{
    protected static string $relationship = 'roles';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->autofocus()
                    ->required()
                    ->maxLength(255),
                Select::make('guard_name')
                    ->options([
                        'web' => 'web',
                        'api' => 'api',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('guard_name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AttachAction::make()
                    ->preloadRecordSelect(),
            ])
            ->recordActions([
                EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                    // Tables\Actions\DetachAction::make(),
                ]),
            ]);
    }
}
