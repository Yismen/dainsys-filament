<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Performance;
use App\Rules\UniqueByColumns;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'downtime';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->columns(2)
                    ->schema([
                        Forms\Components\DatePicker::make('date')
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
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employee', 'full_name')
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('campaign_id')
                            ->searchable()
                            ->relationship(
                                name: 'campaign',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->isDowntime()
                            )
                            ->required(),
                        Forms\Components\TextInput::make('login_time')
                            ->required()
                            ->numeric()
                            ->step(.0001)
                            ->minValue(.10)
                            ->default(0.00000000),
                        Forms\Components\Select::make('downtime_reason_id')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('downtimeReason', 'name'),
                        Forms\Components\Select::make('reporter_id')
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
                    Tables\Columns\TextColumn::make('date')
                        ->date()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('employee.full_name')
                        ->numeric()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('campaign.name')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Campaign: ' . $state)
                        ->sortable(),
                    Tables\Columns\TextColumn::make('login_time')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Login Time: ' . $state)
                        ->sortable(),
                ]),
                Panel::make([
                    Tables\Columns\TextColumn::make('file')
                        ->formatStateUsing(fn ($state) => 'File: ' . $state)
                        ->searchable(),
                    Tables\Columns\TextColumn::make('campaign_goal')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Goal: ' . $state)
                        ->sortable(),
                    Tables\Columns\TextColumn::make('production_time')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Production Time: ' . $state)
                        ->sortable(),
                    Tables\Columns\TextColumn::make('talk_time')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Talk Time: ' . $state)
                        ->sortable(),
                    Tables\Columns\TextColumn::make('billable_time')
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
                    Tables\Columns\TextColumn::make('revenue')
                        ->numeric()
                        ->money()
                        ->formatStateUsing(fn ($state) => 'Revenue: $' . $state)
                        ->sortable(),
                    Tables\Columns\TextColumn::make('downtimeReason.name')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Downtime Reason: ' . $state)
                        ->sortable(),
                    Tables\Columns\TextColumn::make('reporter.name')
                        ->numeric()
                        ->formatStateUsing(fn ($state) => 'Reported By: ' . $state)
                        ->sortable(),
                    Tables\Columns\TextColumn::make('deleted_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->formatStateUsing(fn ($state) => 'Created At: ' . $state)
                        ->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('updated_at')
                        ->dateTime()
                        ->sortable()
                        ->formatStateUsing(fn ($state) => 'Update At: ' . $state)
                        ->toggleable(isToggledHiddenByDefault: true),

                ])
                    ->collapsible()
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
            'index' => Pages\ListDowntimes::route('/'),
            'create' => Pages\CreateDowntime::route('/create'),
            'edit' => Pages\EditDowntime::route('/{record}/edit'),
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
