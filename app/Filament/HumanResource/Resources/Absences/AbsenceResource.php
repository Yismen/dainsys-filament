<?php

namespace App\Filament\HumanResource\Resources\Absences;

use App\Enums\AbsenceStatuses;
use App\Enums\AbsenceTypes;
use App\Filament\HumanResource\Enums\HRNavigationEnum;
use App\Filament\HumanResource\Resources\Absences\Pages\CreateAbsence;
use App\Filament\HumanResource\Resources\Absences\Pages\EditAbsence;
use App\Filament\HumanResource\Resources\Absences\Pages\ListAbsences;
use App\Filament\HumanResource\Resources\Absences\Pages\ViewAbsence;
use App\Filament\HumanResource\Resources\Absences\Schemas\AbsenceForm;
use App\Filament\HumanResource\Resources\Absences\Schemas\AbsenceInfolist;
use App\Filament\HumanResource\Resources\Absences\Tables\AbsencesTable;
use App\Models\Absence;
use App\Notifications\AbsenceReportedNotification;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class AbsenceResource extends Resource
{
    protected static ?string $model = Absence::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static UnitEnum|string|null $navigationGroup = HRNavigationEnum::EMPLOYEES_MANAGEMENT;

    protected static ?int $navigationSort = 8;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema->components(AbsenceForm::schema());
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components(AbsenceInfolist::schema());
    }

    public static function table(Table $table): Table
    {
        return AbsencesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAbsences::route('/'),
            'create' => CreateAbsence::route('/create'),
            'edit' => EditAbsence::route('/{record}/edit'),
            'view' => ViewAbsence::route('/{record}'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->with(['employee', 'creator']);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['employee', 'creator'])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function markAsReportedAction(): Action
    {
        return Action::make('markAsReported')
            ->label('Mark as Reported')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->color('success')
            ->visible(fn (Absence $record): bool => $record->status === AbsenceStatuses::Created)
            ->schema([
                Select::make('type')
                    ->label('Absence Type')
                    ->options(AbsenceTypes::toArray())
                    ->required()
                    ->placeholder('Select type'),
            ])
            ->action(function (Absence $record, array $data): void {
                $type = AbsenceTypes::from($data['type']);
                $record->markAsReported($type);

                if ($record->employee->user) {
                    $record->employee->user->notify(
                        new AbsenceReportedNotification($record, auth()->user())
                    );
                }
            })
            ->successNotificationTitle('Absence marked as reported');
    }
}
