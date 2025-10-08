<?php

namespace App\Filament\App\Resources\EmployeeResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Textarea;
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
use App\Filament\Support\Forms\SuspensionTypeSchema;
use Filament\Resources\RelationManagers\RelationManager;

class SuspensionsRelationManager extends RelationManager
{
    protected static string $relationship = 'suspensions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Select::make('suspension_type_id')
                    ->createOptionForm(SuspensionTypeSchema::toArray())
                    ->createOptionModalHeading('Add New Suspen Type')
                    ->relationship('suspensionType', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('starts_at')
                    ->native(false)
                    ->default(now())
                    ->minDate(now()->subDays(10))
                    ->live()
                    ->required(),
                DatePicker::make('ends_at')
                    ->native(false)
                    ->default(now())
                    ->live()
                    ->minDate(fn (Get $get) => $get('starts_at'))
                    ->required(),
                Textarea::make('comments')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('employees')
            ->columns([
                TextColumn::make('suspensionType.name')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('starts_at')
                    ->date()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->date()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
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
