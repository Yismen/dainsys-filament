<?php

namespace App\Filament\Blog\Resources\CategoryResource\RelationManagers;

use App\Models\Role;
use App\Services\ModelListService;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoryAccessRelationManager extends RelationManager
{
    protected static string $relationship = 'accesses';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Select::make('user_id')
                    ->label('User')
                    ->options(ModelListService::make(\App\Models\User::query()))
                    ->searchable()
                    ->nullable(),
                Select::make('role_id')
                    ->label('Role')
                    ->options(ModelListService::make(Role::query()))
                    ->searchable()
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role.name')
                    ->label('Role')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Granted At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
