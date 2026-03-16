<?php

namespace App\Filament\Supervisor\Resources\Absences;

use App\Enums\AbsenceStatuses;
use App\Enums\AbsenceTypes;
use App\Filament\Supervisor\Resources\Absences\Pages\ManageAbsences;
use App\Models\Absence;
use App\Models\Employee;
use App\Services\ModelListService;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class AbsenceResource extends Resource
{
    protected static ?string $model = Absence::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|UnitEnum|null $navigationGroup = 'Team Management';

    protected static ?int $navigationSort = 8;

    protected static ?string $recordTitleAttribute = 'employee.full_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->label('Employee')
                    ->options(function (): array {
                        $supervisor = Auth::user()?->supervisor;

                        if (! $supervisor) {
                            return [];
                        }

                        return ModelListService::make(
                            model: Employee::query()
                                ->active()
                                ->where('supervisor_id', $supervisor->id),
                            value_field: 'full_name',
                        );
                    })
                    ->searchable()
                    ->required()
                    ->rule(new \App\Rules\UniqueCombination(
                        model: Absence::class,
                        fields: ['employee_id', 'date'],
                        exceptId: request()->route('record')?->id,
                    )),
                DatePicker::make('date')
                    ->required()
                    ->default(now())
                    ->minDate(now()->subMonth())
                    ->maxDate(now()),
                Textarea::make('comment')
                    ->label('Comment')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('employee.full_name')
                    ->label('Employee'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('type')
                    ->badge()
                    ->placeholder('Pending'),
                TextEntry::make('comment')
                    ->placeholder('-'),
                TextEntry::make('creator.name')
                    ->label('Reported By'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('employee.full_name')
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->placeholder('Pending')
                    ->searchable(),
                TextColumn::make('comment')
                    ->limit(50)
                    ->placeholder('-'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(AbsenceStatuses::class)
                    ->searchable(),
                SelectFilter::make('type')
                    ->options(AbsenceTypes::class)
                    ->searchable(),
            ])
            ->actions([
                EditAction::make()
                    ->visible(function (Absence $record): bool {
                        return $record->created_by === auth()->id()
                            && $record->status === AbsenceStatuses::Created;
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAbsences::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $supervisor = Auth::user()?->supervisor;

        if (! $supervisor) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->with('employee')
            ->whereHas('employee', function ($query) use ($supervisor): void {
                $query->where('supervisor_id', $supervisor->id);
            });
    }
}
