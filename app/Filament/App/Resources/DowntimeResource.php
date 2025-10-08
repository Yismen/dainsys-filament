<?php

namespace App\Filament\App\Resources;

use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\App\Resources\DowntimeResource\Pages\ListDowntimes;
use App\Filament\App\Resources\DowntimeResource\Pages\CreateDowntime;
use App\Filament\App\Resources\DowntimeResource\Pages\EditDowntime;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Performance;
use App\Rules\UniqueByColumns;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Traits\WorkforceSupportMenu;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\DowntimeResource\Pages;
use App\Filament\App\Resources\DowntimeResource\RelationManagers;

class DowntimeResource extends Resource
{
    use WorkforceSupportMenu;

    protected static ?string $model = Performance::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'downtime';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('date')
                            ->native(false)
                            ->minDate(now()->subDays(30))
                            ->maxDate(now())
                            ->default(now())
                            ->unique(modifyRuleUsing: function (Unique $rule, callable $get) { // $get callable is used
                                return $rule
                                    ->where('date', $get('date')) // get the current value in the 'school_id' field
                                    ->where('employee_id', $get('employee_id'))
                                    ->where('campaign_id', $get('campaign_id'));
                            }, ignoreRecord: true)
                            ->required(),
                        Select::make('employee_id')
                            ->relationship('employee', 'full_name')
                            ->searchable()
                            ->required(),
                        Select::make('campaign_id')
                            ->searchable()
                            ->relationship(
                                name: 'campaign',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->isDowntime()
                            )
                            ->required(),
                        TextInput::make('login_time')
                            ->required()
                            ->numeric()
                            ->step(.0001)
                            ->minValue(.10)
                            ->default(0.00000000),
                        Select::make('downtime_reason_id')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('downtimeReason', 'name'),
                        Select::make('reporter_id')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('reporter', 'name'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    TextColumn::make('date')
                        ->date()
                        ->sortable(),
                    TextColumn::make('employee.full_name')
                        ->numeric()
                        ->sortable(),
                    TextColumn::make('campaign.name')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Campaign: ' . $state)
                        ->sortable(),
                    TextColumn::make('login_time')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Login Time: ' . $state)
                        ->sortable(),
                ]),
                Panel::make([
                    TextColumn::make('file')
                        ->formatStateUsing(fn ($state) => 'File: ' . $state)
                        ->searchable(),
                    TextColumn::make('campaign_goal')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Goal: ' . $state)
                        ->sortable(),
                    TextColumn::make('production_time')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Production Time: ' . $state)
                        ->sortable(),
                    TextColumn::make('talk_time')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Talk Time: ' . $state)
                        ->sortable(),
                    TextColumn::make('billable_time')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Billable Time: ' . $state)
                        ->sortable(),
                    // Tables\Columns\TextColumn::make('attempts')
                    //     ->numeric()
                    //     ->sortable(),
                    // Tables\Columns\TextColumn::make('contacts')
                    //     ->numeric()
                    //     ->sortable(),
                    // Tables\Columns\TextColumn::make('successes')
                    //     ->numeric()
                    //     ->sortable(),
                    // Tables\Columns\TextColumn::make('upsales')
                    //     ->numeric()
                    //     ->sortable(),
                    TextColumn::make('revenue')
                        ->numeric()
                        ->money()
                        ->formatStateUsing(fn ($state) => 'Revenue: $' . $state)
                        ->sortable(),
                    TextColumn::make('downtimeReason.name')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Downtime Reason: ' . $state)
                        ->sortable(),
                    TextColumn::make('reporter.name')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Reported By: ' . $state)
                        ->sortable(),
                    TextColumn::make('deleted_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->formatStateUsing(fn ($state) => 'Created At: ' . $state)
                        ->toggleable(isToggledHiddenByDefault: true),
                    TextColumn::make('updated_at')
                        ->dateTime()
                        ->sortable()
                        ->formatStateUsing(fn ($state) => 'Update At: ' . $state)
                        ->toggleable(isToggledHiddenByDefault: true),

                ])
                    ->collapsible()
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDowntimes::route('/'),
            'create' => CreateDowntime::route('/create'),
            'edit' => EditDowntime::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('campaign', function (Builder $query) {
                $query->isDowntime();
            })
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
