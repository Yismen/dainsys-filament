<?php

namespace App\Filament\App\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\App\Resources\PerformanceResource\Pages\ListPerformances;
use App\Filament\App\Resources\PerformanceResource\Pages\CreatePerformance;
use App\Filament\App\Resources\PerformanceResource\Pages\EditPerformance;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Performance;
use Filament\Resources\Resource;
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

    protected static string | \BackedEnum | null $navigationIcon =  'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->columns(2)
                    ->schema([
                        TextInput::make('file')
                            ->required()
                            ->visible(false)
                            ->maxLength(255),
                        DatePicker::make('date')
                            ->native(false)
                            ->required(),
                        Select::make('employee_id')
                            ->relationship('employee', 'full_name')
                            ->required(),
                        Select::make('campaign_id')
                            ->relationship('campaign', 'name')
                            ->required(),
                        TextInput::make('campaign_goal')
                            ->required()
                            ->numeric()
                            ->default(0.00000000),
                        TextInput::make('login_time')
                            ->required()
                            ->numeric()
                            ->default(0.00000000),
                        TextInput::make('production_time')
                            ->required()
                            ->numeric()
                            ->default(0.00000000),
                        TextInput::make('talk_time')
                            ->required()
                            ->numeric()
                            ->default(0.00000000),
                        TextInput::make('billable_time')
                            ->required()
                            ->numeric()
                            ->default(0.00000000),
                        TextInput::make('attempts')
                            ->required()
                            ->numeric()
                            ->default(0),
                        TextInput::make('contacts')
                            ->required()
                            ->numeric()
                            ->default(0),
                        TextInput::make('successes')
                            ->required()
                            ->numeric()
                            ->default(0),
                        TextInput::make('upsales')
                            ->required()
                            ->numeric()
                            ->default(0),
                        TextInput::make('revenue')
                            ->required()
                            ->numeric()
                            ->default(0.00000000),
                        Select::make('downtime_reason_id')
                            ->relationship('downtimeReason', 'name'),
                        Select::make('reporter_id')
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
                    TextColumn::make('date')
                        ->date()
                        ->sortable(),
                    TextColumn::make('employee.full_name')
                        ->numeric()
                        ->sortable(),
                    TextColumn::make('login_time')
                        ->numeric()
                        ->getStateUsing(fn ($record) => 'Login Time: ' . $record->login_time)
                        ->summarize(Sum::make())
                        ->sortable(),
                    TextColumn::make('campaign.name')
                        ->getStateUsing(fn ($record) => 'Campaign: ' . $record->campaign->name)
                        ->numeric()
                        ->sortable(),
                    TextColumn::make('campaign.project.name')
                        ->getStateUsing(fn ($record) => 'Project: ' . $record->campaign->project->name)
                        ->numeric()
                        ->sortable()
                ]),
                Panel::make([
                    TextColumn::make('file')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'File Name: ' . $state)
                        ->sortable(),
                    TextColumn::make('campaign_goal')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Campaign Goal: ' . $state)
                        ->sortable(),
                    TextColumn::make('production_time')
                        ->numeric()
                        ->summarize(Sum::make())
                        ->formatStateUsing(fn ($state) => 'Production Time: ' . $state)
                        ->sortable(),
                    TextColumn::make('talk_time')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Talk Time: ' . $state)
                        ->summarize(Sum::make())
                        ->sortable(),
                    TextColumn::make('billable_time')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Billable Time: ' . $state)
                        ->summarize(Sum::make())
                        ->sortable(),
                    TextColumn::make('attempts')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Attempts: ' . $state)
                        ->summarize(Sum::make())
                        ->sortable(),
                    TextColumn::make('contacts')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Contacts: ' . $state)
                        ->summarize(Sum::make())
                        ->sortable(),
                    TextColumn::make('successes')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Successes: ' . $state)
                        ->summarize(Sum::make())
                        ->sortable(),
                    TextColumn::make('upsales')
                        ->numeric()
                        ->summarize(Sum::make())
                        ->formatStateUsing(fn ($state) => 'Upsales: ' . $state)
                        ->sortable(),
                    TextColumn::make('revenue')
                        ->numeric()
                        ->money()
                        ->summarize(Sum::make()->money())
                        ->formatStateUsing(fn ($state) => 'Revenue: $' . $state)
                        ->sortable(),
                    TextColumn::make('downtimeReason.name')
                        ->numeric()
                        ->toggleable()
                        ->formatStateUsing(fn ($state) => 'Downtime Reason: ' . $state)
                        ->sortable(),
                    TextColumn::make('reporter.name')
                        ->numeric()
                        ->toggleable()
                        ->formatStateUsing(fn ($state) => 'Downtime reorted by: ' . $state)
                        ->sortable(),
                    TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->formatStateUsing(fn ($state) => 'Created At: ' . $state),
                    TextColumn::make('updated_at')
                        ->dateTime()
                        ->sortable()
                        ->formatStateUsing(fn ($state) => 'Last Update: ' . $state),
                    TextColumn::make('deleted_at')
                        ->dateTime()
                        ->formatStateUsing(fn ($state) => 'Deleted At: ' . $state)
                        ->sortable(),

                ])->collapsible()
                    ->collapsed()
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
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
            'index' => ListPerformances::route('/'),
            'create' => CreatePerformance::route('/create'),
            'edit' => EditPerformance::route('/{record}/edit'),
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
