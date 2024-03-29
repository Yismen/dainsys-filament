<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Performance;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Filament\Traits\WorkforceSupportMenu;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\PerformanceResource\Pages;
use App\Filament\App\Resources\PerformanceResource\RelationManagers;

class PerformanceResource extends Resource
{
    use WorkforceSupportMenu;

    protected static ?string $model = Performance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('file')
                            ->required()
                            ->visible(false)
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('date')
                            ->required(),
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employee', 'full_name')
                            ->required(),
                        Forms\Components\Select::make('campaign_id')
                            ->relationship('campaign', 'name')
                            ->required(),
                        Forms\Components\TextInput::make('campaign_goal')
                            ->required()
                            ->numeric()
                            ->default(0.00000000),
                        Forms\Components\TextInput::make('login_time')
                            ->required()
                            ->numeric()
                            ->default(0.00000000),
                        Forms\Components\TextInput::make('production_time')
                            ->required()
                            ->numeric()
                            ->default(0.00000000),
                        Forms\Components\TextInput::make('talk_time')
                            ->required()
                            ->numeric()
                            ->default(0.00000000),
                        Forms\Components\TextInput::make('billable_time')
                            ->required()
                            ->numeric()
                            ->default(0.00000000),
                        Forms\Components\TextInput::make('attempts')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('contacts')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('successes')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('upsales')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('revenue')
                            ->required()
                            ->numeric()
                            ->default(0.00000000),
                        Forms\Components\Select::make('downtime_reason_id')
                            ->relationship('downtimeReason', 'name'),
                        Forms\Components\Select::make('reporter_id')
                            ->relationship('reporter', 'name'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('file')
                    ->limit(20)
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.full_name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('campaign.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('campaign_goal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('login_time')
                    ->numeric()
                    ->summarize(Sum::make())
                    ->sortable(),
                Tables\Columns\TextColumn::make('production_time')
                    ->numeric()
                    ->summarize(Sum::make())
                    ->sortable(),
                Tables\Columns\TextColumn::make('talk_time')
                    ->numeric()
                    ->summarize(Sum::make())
                    ->sortable(),
                Tables\Columns\TextColumn::make('billable_time')
                    ->numeric()
                    ->summarize(Sum::make())
                    ->sortable(),
                Tables\Columns\TextColumn::make('attempts')
                    ->numeric()
                    ->summarize(Sum::make())
                    ->sortable(),
                Tables\Columns\TextColumn::make('contacts')
                    ->numeric()
                    ->summarize(Sum::make())
                    ->sortable(),
                Tables\Columns\TextColumn::make('successes')
                    ->numeric()
                    ->summarize(Sum::make())
                    ->sortable(),
                Tables\Columns\TextColumn::make('upsales')
                    ->numeric()
                    ->summarize(Sum::make())
                    ->sortable(),
                Tables\Columns\TextColumn::make('revenue')
                    ->numeric()
                    ->summarize(Sum::make()->money())
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('downtimeReason.name')
                    ->numeric()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reporter.name')
                    ->numeric()
                    ->toggleable()
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
            'index' => Pages\ListPerformances::route('/'),
            'create' => Pages\CreatePerformance::route('/create'),
            'edit' => Pages\EditPerformance::route('/{record}/edit'),
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
