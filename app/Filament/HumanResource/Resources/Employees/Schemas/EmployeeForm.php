<?php

namespace App\Filament\HumanResource\Resources\Employees\Schemas;

use App\Enums\Genders;
use App\Models\Citizenship;
use App\Models\Employee;
use App\Schemas\Filament\HumanResource\HireEmployeeSchema;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
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
                                SpatieMediaLibraryFileUpload::make('profile_photo')
                                    ->label('Photo')
                                    ->collection(Employee::PROFILE_PHOTO_COLLECTION)
                                    ->conversion(Employee::PROFILE_PHOTO_THUMBNAIL_CONVERSION)
                                    ->disk('public')
                                    ->image()
                                    ->imageEditor()
                                    ->circleCropper()
                                    ->maxSize(2048)
                                    ->columnSpanFull(),
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
                                    ->options(ModelListService::get(Citizenship::class))
                                    ->searchable()
                                    ->required(),

                            ]),
                        Section::make('Hiring information')
                            ->columnSpan(1)
                            ->visibleOn('edit')
                            ->schema(HireEmployeeSchema::make()),
                    ]),
            ]);
    }
}
