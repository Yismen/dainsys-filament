<?php

namespace App\Filament\HumanResource\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Suspension;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Support\Forms\EmployeeSchema;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Support\Forms\SuspensionTypeSchema;
use App\Filament\HumanResource\Resources\SuspensionResource\Pages;
use App\Filament\HumanResource\Resources\SuspensionResource\RelationManagers;

class SuspensionResource extends Resource
{
    protected static ?string $model = Suspension::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employee', 'full_name')
                            ->searchable()
                            ->autofocus()
                            ->preload()
                            ->createOptionForm(EmployeeSchema::toArray())
                            ->createOptionModalHeading('Add New Employee')
                            ->required(),
                        Forms\Components\Select::make('suspension_type_id')
                            ->createOptionForm(SuspensionTypeSchema::toArray())
                            ->createOptionModalHeading('Add New Suspen Type')
                            ->relationship('suspensionType', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\DatePicker::make('starts_at')
                            ->default(now())
                            ->maxDate(fn (Get $get) => $get('ends_at'))
                            ->required(),
                        Forms\Components\DatePicker::make('ends_at')
                            ->default(now())
                            ->minDate(fn (Get $get) => $get('starts_at'))
                            ->required(),
                        Forms\Components\Textarea::make('comments')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.full_name')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('suspensionType.name')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->date()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->date()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuspensions::route('/'),
            'create' => Pages\CreateSuspension::route('/create'),
            'view' => Pages\ViewSuspension::route('/{record}'),
            'edit' => Pages\EditSuspension::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
