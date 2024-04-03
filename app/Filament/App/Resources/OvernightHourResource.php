<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\OvernightHour;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Traits\WorkforceSupportMenu;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\OvernightHourResource\Pages;
use App\Filament\App\Resources\OvernightHourResource\RelationManagers;

class OvernightHourResource extends Resource
{
    use WorkforceSupportMenu;

    protected static ?string $model = OvernightHour::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->columns(3)
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->unique(modifyRuleUsing: function (Unique $rule, callable $get) { // $get callable is used
                                return $rule
                                    ->where('date', $get('date')) // get the current value in the 'school_id' field
                                    ->where('employee_id', $get('employee_id'));
                            }, ignoreRecord: true)
                            ->required(),
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employee', 'full_name')
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('hours')
                            ->required()
                            ->numeric(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.full_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('hours')
                    ->numeric()
                    ->sortable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOvernightHours::route('/'),
            'create' => Pages\CreateOvernightHour::route('/create'),
            'edit' => Pages\EditOvernightHour::route('/{record}/edit'),
        ];
    }
}
