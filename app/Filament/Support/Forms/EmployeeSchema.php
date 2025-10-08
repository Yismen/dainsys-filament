<?php

namespace App\Filament\Support\Forms;

use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms;
use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\EmployeeStatus;
use Filament\Forms\Components\Select;
use App\Filament\Support\Forms\AfpSchema;
use App\Filament\Support\Forms\ArsSchema;
use App\Filament\Support\Forms\SiteSchema;
use App\Filament\Support\Forms\ProjectSchema;
use App\Filament\Support\Forms\PositionSchema;
use App\Filament\Support\Forms\SupervisorSchema;
use App\Filament\Support\Forms\CitizenshipSchema;

final class EmployeeSchema
{
    public static function toArray(): array
    {
        return [
            Select::make('site_id')
                ->autofocus()
                ->relationship('site', 'name')
                ->createOptionModalHeading('New Site')
                ->createOptionForm(SiteSchema::toArray())
                ->required(),
            Select::make('project_id')
                ->relationship('project', 'name')
                ->createOptionModalHeading('New Project')
                ->createOptionForm(ProjectSchema::toArray())
                ->required(),
            Select::make('position_id')
                ->relationship('position', 'name')
                ->searchable()
                ->preload()
                ->createOptionModalHeading('New Position')
                ->createOptionForm(PositionSchema::toArray())
                ->getOptionLabelFromRecordUsing(function (Model $record) {
                    return $record->details;
                })
                ->required(),
            Select::make('supervisor_id')
                ->relationship('supervisor', 'name')
                ->createOptionModalHeading('New Supervisor')
                ->createOptionForm(SupervisorSchema::toArray()),
            TextInput::make('first_name')
                ->required()
                ->maxLength(255),
            TextInput::make('second_first_name')
                ->maxLength(255),
            TextInput::make('last_name')
                ->required()
                ->maxLength(255),
            TextInput::make('second_last_name')
                ->maxLength(255),
            TextInput::make('personal_id')
                ->required()
                ->maxLength(11),
            TextInput::make('cellphone')
                ->tel()
                ->required()
                ->maxLength(20),
            DateTimePicker::make('hired_at')
                ->default(now())
                ->maxDate(now()->endOfDay())
                ->disabledDates([
                    today()->subDay(),
                    now()->addDay()
                ])
                ->required(),
            DatePicker::make('date_of_birth')
                ->native(false)
                ->default(now()->subYears(20))
                ->maxDate(now()->addDays(5))
                ->required(),
            Select::make('status')
                ->required()
                ->options(EmployeeStatus::toArray())
                ->enum(EmployeeStatus::class)
                ->visibleOn('view')
                // ->hidden()
                ->default(EmployeeStatus::Current),
            Select::make('gender')
                ->required()
                ->options(Gender::class)
                ->enum(Gender::class)
                ->default(Gender::Male),
            Select::make('citizenship_id')
                ->relationship('citizenship', 'name')
                ->createOptionModalHeading('New Citizenship')
                ->createOptionForm(CitizenshipSchema::toArray())
                ->required(),
            Select::make('marriage')
                ->required()
                ->options(MaritalStatus::toArray())
                ->enum(MaritalStatus::class)
                ->default(MaritalStatus::Single),
            Toggle::make('kids')
                ->required(),
            TextInput::make('punch')
                ->required()
                ->unique(ignoreRecord: true)
                ->minLength(4)
                ->maxLength(10),
            Select::make('afp_id')
                ->relationship('afp', 'name')
                ->createOptionModalHeading('New Afp')
                ->createOptionForm(AfpSchema::toArray()),
            Select::make('ars_id')
                ->relationship('ars', 'name')
                ->createOptionModalHeading('New Ars')
                ->createOptionForm(ArsSchema::toArray()),
        ];
    }
}
