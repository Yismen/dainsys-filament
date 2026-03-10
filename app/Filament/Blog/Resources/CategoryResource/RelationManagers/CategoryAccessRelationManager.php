<?php

namespace App\Filament\Blog\Resources\CategoryResource\RelationManagers;

use App\Models\Role;
use App\Services\ModelListService;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
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
                CheckboxList::make('user_id')
                    ->label('User')
                    ->options(ModelListService::make(\App\Models\User::query()))
                    ->searchable()
                    ->bulkToggleable(true)
                    ->nullable(),
                CheckboxList::make('role_id')
                    ->label('Role')
                    ->options(ModelListService::make(Role::query()))
                    ->searchable()
                    ->bulkToggleable(true)
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
                CreateAction::make()
                    ->action(function ($livewire, $data) {
                        foreach ($data['user_id'] ?? [] as $userId) {
                            $livewire->ownerRecord->accesses()->firstOrCreate([
                                'user_id' => $userId,
                            ]);
                        }
                        foreach ($data['role_id'] ?? [] as $roleId) {
                            $livewire->ownerRecord->accesses()->firstOrCreate([
                                'role_id' => $roleId,
                            ]);
                        }
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
