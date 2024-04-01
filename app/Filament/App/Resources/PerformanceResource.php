<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Performance;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Filament\Traits\WorkforceSupportMenu;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\PerformanceResource\Pages;

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
            ->defaultSort('date', 'desc')
            ->columns([
                Split::make([
                    Tables\Columns\TextColumn::make('date')
                        ->date()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('employee.full_name')
                        ->numeric()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('login_time')
                        ->numeric()
                        ->getStateUsing(fn ($record) => 'Login Time: ' . $record->login_time)
                        ->summarize(Sum::make())
                        ->sortable(),
                    Tables\Columns\TextColumn::make('campaign.name')
                        ->getStateUsing(fn ($record) => 'Campaign: ' . $record->campaign->name)
                        ->numeric()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('campaign.project.name')
                        ->getStateUsing(fn ($record) => 'Project: ' . $record->campaign->project->name)
                        ->numeric()
                        ->sortable()
                ]),
                Panel::make([
                    Tables\Columns\TextColumn::make('file')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'File Name: ' . $state)
                        ->sortable(),
                    Tables\Columns\TextColumn::make('campaign_goal')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Campaign Goal: ' . $state)
                        ->sortable(),
                    Tables\Columns\TextColumn::make('production_time')
                        ->numeric()
                        ->summarize(Sum::make())
                        ->formatStateUsing(fn ($state) => 'Production Time: ' . $state)
                        ->sortable(),
                    Tables\Columns\TextColumn::make('talk_time')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Talk Time: ' . $state)
                        ->summarize(Sum::make())
                        ->sortable(),
                    Tables\Columns\TextColumn::make('billable_time')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Billable Time: ' . $state)
                        ->summarize(Sum::make())
                        ->sortable(),
                    Tables\Columns\TextColumn::make('attempts')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Attempts: ' . $state)
                        ->summarize(Sum::make())
                        ->sortable(),
                    Tables\Columns\TextColumn::make('contacts')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Contacts: ' . $state)
                        ->summarize(Sum::make())
                        ->sortable(),
                    Tables\Columns\TextColumn::make('successes')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Successes: ' . $state)
                        ->summarize(Sum::make())
                        ->sortable(),
                    Tables\Columns\TextColumn::make('upsales')
                        ->numeric()
                        ->summarize(Sum::make())
                        ->formatStateUsing(fn ($state) => 'Upsales: ' . $state)
                        ->sortable(),
                    Tables\Columns\TextColumn::make('revenue')
                        ->numeric()
                        ->money()
                        ->summarize(Sum::make()->money())
                        ->formatStateUsing(fn ($state) => 'Revenue: $' . $state)
                        ->sortable(),
                    Tables\Columns\TextColumn::make('downtimeReason.name')
                        ->numeric()
                        ->toggleable()
                        ->formatStateUsing(fn ($state) => 'Downtime Reason: ' . $state)
                        ->sortable(),
                    Tables\Columns\TextColumn::make('reporter.name')
                        ->numeric()
                        ->toggleable()
                        ->formatStateUsing(fn ($state) => 'Downtime reorted by: ' . $state)
                        ->sortable(),
                    Tables\Columns\TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->formatStateUsing(fn ($state) => 'Created At: ' . $state),
                    Tables\Columns\TextColumn::make('updated_at')
                        ->dateTime()
                        ->sortable()
                        ->formatStateUsing(fn ($state) => 'Last Update: ' . $state),
                    Tables\Columns\TextColumn::make('deleted_at')
                        ->dateTime()
                        ->formatStateUsing(fn ($state) => 'Deleted At: ' . $state)
                        ->sortable(),

                ])->collapsible()
                    ->collapsed()
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
        return [];
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
