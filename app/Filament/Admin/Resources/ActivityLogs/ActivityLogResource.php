<?php

namespace App\Filament\Admin\Resources\ActivityLogs;

use App\Filament\Admin\Resources\ActivityLogs\Pages\ManageActivityLogs;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;
use UnitEnum;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCursorArrowRays;

    protected static ?string $recordTitleAttribute = 'description';

    protected static string|UnitEnum|null $navigationGroup = 'System';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('description')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextEntry::make('log_name'),
                TextEntry::make('description'),
                TextEntry::make('subject_type'),
                TextEntry::make('subject_id'),
                TextEntry::make('causer_type'),
                TextEntry::make('causer.name'),
                TextEntry::make('event'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('properties')
                    ->columnSpanFull()
                    ->state(function (Activity $record) {
                        return \view('filament.partials.infolists.json', [
                            'json' => $record->properties,
                        ]);
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('filament.date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('log_name')
                    ->label(__('filament.log'))
                    ->searchable(isIndividual: true),
                TextColumn::make('subject_type')
                    ->label(__('filament.subject'))
                    ->wrap()
                    ->sortable()
                    ->searchable(isIndividual: true),
                TextColumn::make('subject_id')
                    ->label(__('filament.subject_id'))
                    ->wrap()
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('causer_type')
                    ->label(__('filament.causer_type'))
                    ->wrap()
                    ->sortable()
                    ->searchable(isIndividual: true)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('causer.name')
                    ->label(__('filament.user'))
                    ->wrap()
                    ->searchable(isIndividual: true),
                TextColumn::make('properties.user_id')
                    ->label(__('filament.user_id'))
                    ->state(fn (Activity $record): ?string => $record->getExtraProperty('user_id'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('properties.name')
                    ->label(__('filament.name'))
                    ->state(fn (Activity $record): ?string => $record->getExtraProperty('name'))
                    ->searchable(isIndividual: true)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('properties.email')
                    ->label(__('filament.email'))
                    ->state(fn (Activity $record): ?string => $record->getExtraProperty('email'))
                    ->searchable(isIndividual: true)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('properties.ip_address')
                    ->label(__('filament.ip_address'))
                    ->state(fn (Activity $record): ?string => $record->getExtraProperty('ip_address'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('properties.browser')
                    ->label(__('filament.browser'))
                    ->state(fn (Activity $record): ?string => $record->getExtraProperty('browser'))
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('event')
                    ->label(__('filament.event'))
                    ->searchable(isIndividual: true),
                TextColumn::make('updated_at')
                    ->label(__('filament.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('log_name')
                    ->label(__('filament.log'))
                    ->options([
                        'authentication' => 'Authentication',
                        'default' => 'Default',
                    ]),
                SelectFilter::make('event')
                    ->label(__('filament.event'))
                    ->options([
                        'login' => 'Login',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                        'created' => 'Created',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                // EditAction::make(),
                // DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageActivityLogs::route('/'),
        ];
    }
}
