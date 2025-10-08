<?php

namespace App\Filament\App\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\OvernightHourResource\Pages\ListOvernightHours;
use App\Filament\App\Resources\OvernightHourResource\Pages\CreateOvernightHour;
use App\Filament\App\Resources\OvernightHourResource\Pages\EditOvernightHour;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\OvernightHour;
use Filament\Resources\Resource;
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

    protected static string | \BackedEnum | null $navigationIcon =  'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->columns(3)
                    ->schema([
                        DatePicker::make('date')
                            ->native(false)
                            ->unique(modifyRuleUsing: function (Unique $rule, callable $get) { // $get callable is used
                                return $rule
                                    ->where('date', $get('date')) // get the current value in the 'school_id' field
                                    ->where('employee_id', $get('employee_id'));
                            }, ignoreRecord: true)
                            ->required(),
                        Select::make('employee_id')
                            ->relationship('employee', 'full_name')
                            ->searchable()
                            ->required(),
                        TextInput::make('hours')
                            ->required()
                            ->numeric(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->sortable(),
                TextColumn::make('hours')
                    ->numeric()
                    ->sortable(),
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
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListOvernightHours::route('/'),
            'create' => CreateOvernightHour::route('/create'),
            'edit' => EditOvernightHour::route('/{record}/edit'),
        ];
    }
}
