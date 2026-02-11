<?php

namespace App\Filament\HumanResource\Resources\Employees\Schemas;

use App\Enums\EmployeeStatuses;
use App\Enums\Genders;
use App\Models\Citizenship;
use App\Models\Position;
use App\Models\Project;
use App\Models\Site;
use App\Models\Supervisor;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Section::make('Employee information')
                            ->columnSpan(fn (string $operation) => $operation === 'create' ? 3 : 2)
                            ->columns(2)
                            ->schema([
                                TextEntry::make('status')
                                    ->columnSpanFull()
                                    ->badge()
                                    ->hiddenLabel()
                                    ->visibleOn('edit'),
                                TextInput::make('first_name')
                                    ->autofocus()
                                    ->maxLength(255)
                                    ->required(),
                                TextInput::make('second_first_name')
                                    ->maxLength(255),
                                TextInput::make('last_name')
                                    ->maxLength(255)
                                    ->required(),
                                TextInput::make('second_last_name')
                                    ->maxLength(255),
                                Select::make('personal_id_type')
                                    ->options(\App\Enums\PersonalIdTypes::class)
                                    ->required(),
                                TextInput::make('personal_id')
                                    ->minLength(10)
                                    ->maxLength(11)
                                    ->unique(ignoreRecord: true)
                                    ->required(),
                                DatePicker::make('date_of_birth')
                                    ->default(now()->subYears(18)->format('Y-m-d'))
                                    ->maxDate(now()->subYears(16)->format('Y-m-d'))
                                    ->required(),
                                TextInput::make('cellphone')
                                    ->unique(ignoreRecord: true)
                                    ->minLength(10)
                                    ->maxLength(20)
                                    ->tel()
                                    ->required(),
                                TextInput::make('secondary_phone')
                                    ->nullable()
                                    ->maxLength(20)
                                    ->tel(),
                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(200),
                                TextInput::make('address')
                                    ->required()
                                    ->maxLength(800),
                                Select::make('gender')
                                    ->options(Genders::class)
                                    ->required(),
                                Toggle::make('has_kids')
                                    ->required(),
                                Select::make('citizenship_id')
                                    ->relationship('citizenship', 'name')
                                    ->options(ModelListService::get(Citizenship::class))
                                    ->required(),

                            ]),
                        Section::make('Hiring information')
                            ->columnSpan(1)
                            ->visibleOn('edit')
                            ->schema([

                                Select::make('site_id')
                                    ->relationship('site', 'name')
                                    ->searchable()
                                    ->disabled(fn ($record) => $record->status === EmployeeStatuses::Created)
                                    ->options(ModelListService::make(Site::query())),
                                Select::make('project_id')
                                    ->relationship('project', 'name')
                                    ->disabled(fn ($record) => $record->status === EmployeeStatuses::Created)
                                    ->searchable()
                                    ->options(ModelListService::make(Project::query())),
                                Select::make('position_id')
                                    ->relationship('position', 'name')
                                    ->disabled(fn ($record) => $record->status === EmployeeStatuses::Created)
                                    ->searchable()
                                    ->options(ModelListService::make(Position::query())),
                                Select::make('supervisor_id')
                                    ->relationship('supervisor', 'name')
                                    ->disabled(fn ($record) => $record->status === EmployeeStatuses::Created)
                                    ->options(ModelListService::make(Supervisor::query())),
                                DateTimePicker::make('hired_at')
                                    ->nullable()
                                    ->disabled(fn ($record) => $record->status === EmployeeStatuses::Created),
                                TextInput::make('internal_id')
                                    ->nullable()
                                    ->unique(ignoreRecord: true)
                                    ->minLength(4)
                                    ->maxLength(20),
                            ]),
                    ]),
            ]);
    }
}
