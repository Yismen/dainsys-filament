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
use Filament\Forms\Components\KeyValue;
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
                                    ->label(__('filament.first_name'))
                                    ->autofocus()
                                    ->maxLength(255)
                                    ->required(),
                                TextInput::make('second_first_name')
                                    ->label(__('filament.second_first_name'))
                                    ->maxLength(255),
                                TextInput::make('last_name')
                                    ->label(__('filament.last_name'))
                                    ->maxLength(255)
                                    ->required(),
                                TextInput::make('second_last_name')
                                    ->label(__('filament.second_last_name'))
                                    ->maxLength(255),
                                Select::make('personal_id_type')
                                    ->label(__('filament.personal_id_type'))
                                    ->options(PersonalIdTypes::class)
                                    ->required(),
                                TextInput::make('personal_id')
                                    ->label(__('filament.personal_id'))
                                    ->minLength(10)
                                    ->maxLength(11)
                                    ->unique(ignoreRecord: true)
                                    ->required(),
                                DatePicker::make('date_of_birth')
                                    ->label(__('filament.date_of_birth'))
                                    ->default(now()->subYears(18)->format('Y-m-d'))
                                    ->maxDate(now()->subYears(16)->format('Y-m-d'))
                                    ->required(),
                                TextInput::make('cellphone')
                                    ->label(__('filament.cellphone'))
                                    ->unique(ignoreRecord: true)
                                    ->minLength(10)
                                    ->maxLength(20)
                                    ->tel()
                                    ->required(),
                                TextInput::make('secondary_phone')
                                    ->label(__('filament.secondary_phone'))
                                    ->nullable()
                                    ->maxLength(20)
                                    ->tel(),
                                TextInput::make('email')
                                    ->label(__('filament.email'))
                                    ->email()
                                    ->required()
                                    ->maxLength(200),
                                TextInput::make('address')
                                    ->label(__('filament.address'))
                                    ->required()
                                    ->maxLength(800),
                                Select::make('gender')
                                    ->label(__('filament.gender'))
                                    ->options(Genders::class)
                                    ->required(),
                                Toggle::make('has_kids')
                                    ->label(__('filament.has_kids'))
                                    ->required(),
                                Select::make('citizenship_id')
                                    ->label(__('filament.citizenship'))
                                    ->options(ModelListService::get(Citizenship::class))
                                    ->searchable()
                                    ->required(),
                                SpatieMediaLibraryFileUpload::make('profile_photo')
                                    ->label(__('filament.profile_photo'))
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
                        Section::make(__('filament.hiring_information'))
                            ->columnSpan(1)
                            ->visibleOn('edit')
                            ->schema(
                                array_merge(
                                    HireEmployeeSchema::make(),
                                    [
                                        Toggle::make('is_universal')
                                            ->dehydrated(false)
                                            ->label(__('filament.is_universal'))
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

                                                $record->refresh();

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
                        Fieldset::make(__('filament.bank_account_information'))
                            ->columns(2)
                            ->relationship(
                                'bankAccount',
                                condition: fn (?array $state) => isset($state['bank_id']) || isset($state['account'])
                            )
                            ->visibleOn('edit')
                            ->schema([
                                Select::make('bank_id')
                                    ->label(__('filament.bank'))
                                    ->options(
                                        ModelListService::make(Bank::query())
                                    )
                                    ->searchable()
                                    ->requiredWith('account'),
                                TextInput::make('account')
                                    ->label(__('filament.account'))
                                    ->minLength(5)
                                    ->maxLength(50)
                                    ->trim()
                                    ->unique(ignoreRecord: true, table: (new BankAccount)->getTable())
                                    ->requiredWith('bank_id'),
                            ]),

                        Fieldset::make(__('filament.social_security_information'))
                            ->columns(3)
                            ->relationship(
                                'socialSecurity',
                                condition: fn (?array $state) => isset($state['afp_id']) || isset($state['ars_id']) || isset($state['number'])
                            )
                            ->visibleOn('edit')
                            ->schema([
                                Select::make('afp_id')
                                    ->label(__('filament.afp'))
                                    ->options(ModelListService::make(Afp::query()))
                                    ->searchable()
                                    ->requiredWith('ars_id'),
                                Select::make('ars_id')
                                    ->label(__('filament.ars'))
                                    ->options(ModelListService::make(Ars::query()))
                                    ->searchable()
                                    ->requiredWith('afp_id'),
                                TextInput::make('number')
                                    ->label(__('filament.tss_number'))
                                    ->minLength(3)
                                    ->maxLength(50)
                                    ->trim()
                                    ->unique(ignoreRecord: true, table: (new SocialSecurity)->getTable(), column: 'number'),
                            ]),
                    ]),
                Section::make(__('filament.extra_attributes'))
                    ->columnSpanFull()
                    ->collapsible()
                    ->collapsed(false)
                    ->visibleOn('edit')
                    ->schema([
                        KeyValue::make('extra_attributes')
                            ->label(__('filament.extra_attributes'))
                            ->keyLabel(__('filament.key'))
                            ->valueLabel(__('filament.value'))
                            ->addActionLabel(__('filament.add_attribute')),
                    ]),
            ]);
    }
}
