<?php

namespace App\Filament\Support\Forms;

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
            Forms\Components\Select::make('site_id')
                ->autofocus()
                ->relationship('site', 'name')
                ->createOptionModalHeading('New Site')
                ->createOptionForm(SiteSchema::toArray())
                ->required(),
            Forms\Components\Select::make('project_id')
                ->relationship('project', 'name')
                ->createOptionModalHeading('New Project')
                ->createOptionForm(ProjectSchema::toArray())
                ->required(),
            Forms\Components\Select::make('position_id')
                ->relationship('position', 'name')
                ->searchable()
                ->preload()
                ->createOptionModalHeading('New Position')
                ->createOptionForm(PositionSchema::toArray())
                ->getOptionLabelFromRecordUsing(function (\Illuminate\Database\Eloquent\Model $record) {
                    return $record->details;
                })
                ->required(),
            Forms\Components\Select::make('supervisor_id')
                ->relationship('supervisor', 'name')
                ->createOptionModalHeading('New Supervisor')
                ->createOptionForm(SupervisorSchema::toArray()),
            Forms\Components\TextInput::make('first_name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('second_first_name')
                ->maxLength(255),
            Forms\Components\TextInput::make('last_name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('second_last_name')
                ->maxLength(255),
            Forms\Components\TextInput::make('personal_id')
                ->required()
                ->maxLength(11),
            Forms\Components\TextInput::make('cellphone')
                ->tel()
                ->required()
                ->maxLength(20),
            Forms\Components\DateTimePicker::make('hired_at')
                ->default(now())
                ->maxDate(now()->endOfDay())
                ->disabledDates([
                    today()->subDay(),
                    now()->addDay()
                ])
                ->required(),
            Forms\Components\DatePicker::make('date_of_birth')
                ->native(false)
                ->default(now()->subYears(20))
                ->maxDate(now()->addDays(5))
                ->required(),
            Forms\Components\Select::make('status')
                ->required()
                ->options(EmployeeStatus::toArray())
                ->enum(EmployeeStatus::class)
                ->visibleOn('view')
                // ->hidden()
                ->default(EmployeeStatus::Current),
            Forms\Components\Select::make('gender')
                ->required()
                ->options(Gender::class)
                ->enum(Gender::class)
                ->default(Gender::Male),
            Forms\Components\Select::make('citizenship_id')
                ->relationship('citizenship', 'name')
                ->createOptionModalHeading('New Citizenship')
                ->createOptionForm(CitizenshipSchema::toArray())
                ->required(),
            Forms\Components\Select::make('marriage')
                ->required()
                ->options(MaritalStatus::toArray())
                ->enum(MaritalStatus::class)
                ->default(MaritalStatus::Single),
            Forms\Components\Toggle::make('kids')
                ->required(),
            Forms\Components\Select::make('afp_id')
                ->relationship('afp', 'name')
                ->createOptionModalHeading('New Afp')
                ->createOptionForm(AfpSchema::toArray()),
            Forms\Components\Select::make('ars_id')
                ->relationship('ars', 'name')
                ->createOptionModalHeading('New Ars')
                ->createOptionForm(ArsSchema::toArray()),
        ];
    }
}
