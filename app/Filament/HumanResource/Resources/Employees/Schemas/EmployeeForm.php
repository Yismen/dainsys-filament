<?php

namespace App\Filament\HumanResource\Resources\Employees\Schemas;

use App\Enums\Genders;
use App\Enums\PersonalIdTypes;
use App\Models\Afp;
use App\Models\Ars;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Citizenship;
use App\Models\Employee;
use App\Models\SocialSecurity;
use App\Schemas\Filament\HumanResource\HireEmployeeSchema;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Predis\Command\Argument\Server\To;

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
                                    ->options(PersonalIdTypes::class)
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
                                SpatieMediaLibraryFileUpload::make('profile_photo')
                                    ->label('Photo')
                                    ->collection(Employee::PROFILE_PHOTO_COLLECTION)
                                    ->conversion(Employee::PROFILE_PHOTO_THUMBNAIL_CONVERSION)
                                    ->disk('public')
                                    ->image()
                                    ->avatar()
                                    ->imageEditor()
                                    ->circleCropper()
                                    ->maxSize(2048)
                                    ->columnSpanFull(),

                            ]),
                        Section::make('Hiring information')
                            ->columnSpan(1)
                            ->visibleOn('edit')
                            ->schema(
                                array_merge(
                                    HireEmployeeSchema::make(),
                                    [
                                        Toggle::make('is_universal')
                                            ->dehydrated(false)
                                            ->label('Is Universal Employee')
                                            ->live()
                                            ->default(fn (?Employee $record) => $record?->isUniversal() ?? false)
                                            ->afterStateUpdated(function ($state, ?Employee $record) {
                                                if ($state === false) {
                                                    $record?->universal()->forceDelete();

                                                    Notification::make()
                                                        ->title('Universal employee record deleted')
                                                        ->danger()
                                                        ->send();

                                                    return null;
                                                }

                                                $record?->universal()->firstOrCreate([
                                                    'date_since' => now(),
                                                ]);

                                                Notification::make()
                                                    ->title('Universal employee record created')
                                                    ->warning()
                                                    ->send();
                                            }),
                                    ]
                                )
                            ),
                    ]),
                Grid::make()
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Fieldset::make('Bank Account Information')
                            ->columns(2)
                            ->relationship(
                                'bankAccount',
                                condition: fn (?array $state) => isset($state['bank_id']) || isset($state['account'])
                            )
                            ->visibleOn('edit')
                            ->schema([
                                Select::make('bank_id')
                                    ->label('Bank')
                                    ->options(
                                        ModelListService::make(Bank::query())
                                    )
                                    ->searchable()
                                    ->requiredWith('account'),
                                TextInput::make('account')
                                    ->minLength(5)
                                    ->maxLength(50)
                                    ->trim()
                                    ->unique(ignoreRecord: true, table: (new BankAccount)->getTable())
                                    ->requiredWith('bank_id'),
                            ]),

                        Fieldset::make('Social Security Information')
                            ->columns(3)
                            ->relationship(
                                'socialSecurity',
                                condition: fn (?array $state) => isset($state['afp_id']) || isset($state['ars_id']) || isset($state['number'])
                            )
                            ->visibleOn('edit')
                            ->schema([
                                Select::make('afp_id')
                                    ->label('AFP')
                                    ->options(ModelListService::make(Afp::query()))
                                    ->searchable()
                                    ->requiredWith('ars_id'),
                                Select::make('ars_id')
                                    ->label('ARS')
                                    ->options(ModelListService::make(Ars::query()))
                                    ->searchable()
                                    ->requiredWith('afp_id'),
                                TextInput::make('number')
                                    ->label('TSS Number')
                                    ->minLength(3)
                                    ->maxLength(50)
                                    ->trim()
                                    ->unique(ignoreRecord: true, table: (new SocialSecurity)->getTable(), column: 'number'),
                            ]),
                    ]),
            ]);
    }
}
