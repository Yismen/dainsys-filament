<?php

namespace App\Filament\Workforce\Resources\Downtimes;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Validation\Rules\Unique;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Workforce\Resources\Downtimes\Pages\ManageDowntimes;
use App\Models\Performance;

class DowntimeResource extends Resource
{
    protected static ?string $model = Performance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
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
            'index' => ManageDowntimes::route('/'),
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
