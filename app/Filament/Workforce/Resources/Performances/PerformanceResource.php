<?php

namespace App\Filament\Workforce\Resources\Performances;

use App\Filament\Workforce\Resources\Performances\Pages\ManagePerformances;
use App\Models\Performance;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PerformanceResource extends Resource
{
    protected static ?string $model = Performance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('file'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('employee.id')
                    ->label('Employee'),
                TextEntry::make('campaign.name')
                    ->label('Campaign'),
                TextEntry::make('campaign_goal')
                    ->numeric(),
                TextEntry::make('login_time')
                    ->numeric(),
                TextEntry::make('production_time')
                    ->numeric(),
                TextEntry::make('talk_time')
                    ->numeric(),
                TextEntry::make('billable_time')
                    ->numeric(),
                TextEntry::make('attempts')
                    ->numeric(),
                TextEntry::make('contacts')
                    ->numeric(),
                TextEntry::make('successes')
                    ->numeric(),
                TextEntry::make('upsales')
                    ->numeric(),
                TextEntry::make('revenue')
                    ->numeric(),
                TextEntry::make('downtimeReason.name')
                    ->label('Downtime reason')
                    ->placeholder('-'),
                TextEntry::make('reporter.name')
                    ->label('Reporter')
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Performance $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('file')
                    ->searchable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('employee.id')
                    ->searchable(),
                TextColumn::make('campaign.name')
                    ->searchable(),
                TextColumn::make('campaign_goal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('login_time')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('production_time')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('talk_time')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('billable_time')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('attempts')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('contacts')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('successes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('upsales')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('revenue')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('downtimeReason.name')
                    ->searchable(),
                TextColumn::make('reporter.name')
                    ->searchable(),
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
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePerformances::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
