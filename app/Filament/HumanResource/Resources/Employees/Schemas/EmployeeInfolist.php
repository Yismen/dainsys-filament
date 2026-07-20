<?php

namespace App\Filament\HumanResource\Resources\Employees\Schemas;

use App\Models\Employee;
use Carbon\Carbon;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema(
                        self::personalInformationSection(),
                    ),
                Grid::make(1)
                    ->schema([
                        self::jobInformationSection(),

                        self::bankAccountInformationSection(),

                        self::socialSecuritySection(),

                        self::extraAttributesSection(),
                    ]),

                Grid::make(1)
                    ->columnSpanFull()
                    ->schema([
                        self::pastSuspensionsSection(),

                        self::last30DaysAbsencesSection(),

                        self::pastHiresSection(),

                        self::pastTerminationsSection(),
                    ]),

            ]);
    }

    private static function personalInformationSection(): array
    {
        return [
            SpatieMediaLibraryImageEntry::make('profile_photo')
                ->label(__('filament.profile_photo'))
                ->collection(Employee::PROFILE_PHOTO_COLLECTION)
                ->conversion(Employee::PROFILE_PHOTO_THUMBNAIL_CONVERSION)
                ->defaultImageUrl(fn (Employee $record): string => $record->getProfilePhotoPlaceholderUrl())
                ->circular()
                ->openUrlInNewTab()
                ->url(fn (Employee $record) => $record->getProfilePhotoUrl()),
            TextEntry::make('id')
                ->label(__('filament.id')),
            TextEntry::make('full_name')
                ->label(__('filament.full_name'))
                ->placeholder('-'),
            TextEntry::make('status')
                ->label(__('filament.status'))
                ->badge(),
            TextEntry::make('personal_id_type')
                ->label(__('filament.personal_id_type'))
                ->badge()
                ->color(fn ($value) => $value?->getColor()),
            TextEntry::make('personal_id')
                ->label(__('filament.personal_id')),
            TextEntry::make('date_of_birth')
                ->label(__('filament.date_of_birth'))
                ->date(),
            TextEntry::make('gender')
                ->label(__('filament.gender'))
                ->badge()
                ->icon(fn ($value) => $value?->getIcon())
                ->color(fn ($value) => $value?->getColor()),
            TextEntry::make('cellphone')
                ->label(__('filament.cellphone')),
            IconEntry::make('has_kids')
                ->label(__('filament.has_kids'))
                ->boolean(),
            TextEntry::make('citizenship.name')
                ->label(__('filament.citizenship'))
                ->icon('heroicon-o-flag'),
            TextEntry::make('internal_id')
                ->label(__('filament.internal_id')),
            TextEntry::make('deleted_at')
                ->label(__('filament.deleted_at'))
                ->dateTime()
                ->visible(fn (Employee $record): bool => $record->trashed()),
            TextEntry::make('created_at')
                ->label(__('filament.created_at'))
                ->dateTime()
                ->placeholder('-'),
            TextEntry::make('updated_at')
                ->label(__('filament.updated_at'))
                ->dateTime()
                ->placeholder('-'),
        ];
    }

    private static function bankAccountInformationSection(): Section
    {
        return Section::make(__('filament.bank_account_information'))
            ->columns([
                'default' => 1,
                'sm' => 2,
            ])
            ->collapsible()
            ->collapsed(fn (Employee $record) => ! $record->bankAccount()->exists())
            ->columnSpanFull()
            ->components([
                TextEntry::make('bankAccount.bank.name')
                    ->label(__('filament.bank')),
                TextEntry::make('bankAccount.account')
                    ->label(__('filament.account')),
            ]);
    }

    private static function socialSecuritySection(): Section
    {
        return Section::make(__('filament.social_security_information'))
            ->columns([
                'default' => 1,
                'sm' => 2,
                'md' => 3,
            ])
            ->collapsible()
            ->collapsed(fn (Employee $record) => ! $record->socialSecurity()->exists())
            ->columnSpanFull()
            ->components([
                TextEntry::make('socialSecurity.afp.name')
                    ->label(__('filament.afp')),
                TextEntry::make('socialSecurity.ars.name')
                    ->label(__('filament.ars')),
                TextEntry::make('socialSecurity.number')
                    ->label(__('filament.tss_number')),
            ]);
    }

    private static function extraAttributesSection(): Section
    {
        return Section::make(__('filament.extra_attributes'))
            ->collapsible()
            ->collapsed()
            ->columnSpanFull()
            ->visible(fn (Employee $record): bool => filled($record->extra_attributes))
            ->schema([
                KeyValueEntry::make('extra_attributes')
                    ->label(__('filament.extra_attributes')),
            ]);
    }

    private static function jobInformationSection(): Section
    {
        return Section::make(__('filament.job_information'))
            ->columns([
                'default' => 1,
                'sm' => 2,
            ])
            ->collapsible()
            ->columnSpanFull()
            ->components([
                TextEntry::make('hired_at')
                    ->label(__('filament.hired_at'))
                    ->date(),
                TextEntry::make('site.name')
                    ->label(__('filament.site')),
                TextEntry::make('project.name')
                    ->label(__('filament.project')),
                TextEntry::make('position.details')
                    ->label(__('filament.position')),
                TextEntry::make('supervisor.name')
                    ->label(__('filament.supervisor')),
            ]);
    }

    private static function pastSuspensionsSection(): Section
    {
        return Section::make(__('filament.suspensions_history'))
            ->columns([
                'default' => 1,
            ])
            ->collapsible()
            ->collapsed(fn (Employee $record) => ! $record->suspensions()->exists())
            ->columnSpanFull()
            ->components([
                RepeatableEntry::make('suspensions')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 5,
                    ])
                    ->table([
                        TableColumn::make(__('filament.suspension_type')),
                        TableColumn::make(__('filament.starts_at')),
                        TableColumn::make(__('filament.ends_at')),
                        TableColumn::make(__('filament.duration_days')),
                        TableColumn::make(__('filament.status')),
                        TableColumn::make(__('filament.comment')),
                    ])
                    ->schema([
                        TextEntry::make('suspensionType.name')
                            ->label(__('filament.suspension_type')),
                        TextEntry::make('starts_at')
                            ->label(__('filament.starts_at'))
                            ->date(),
                        TextEntry::make('ends_at')
                            ->label(__('filament.ends_at'))
                            ->date(),
                        TextEntry::make('duration')
                            ->label(__('filament.duration_days')),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn ($record) => $record->status->getColor()),
                        TextEntry::make('comment')
                            ->wrap()
                            ->label(__('filament.comment'))
                            ->limit(50)
                            ->tooltip(fn ($record) => $record->comment),
                    ]),
            ]);
    }

    private static function pastHiresSection(): Section
    {
        return Section::make(__('filament.hires_history'))
            ->columns([
                'default' => 1,
            ])
            ->collapsible()
            ->collapsed(fn (Employee $record) => ! $record->hires()->exists())
            ->columnSpanFull()
            ->components([
                RepeatableEntry::make('hires')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 5,
                    ])
                    ->table([
                        TableColumn::make(__('filament.date')),
                        TableColumn::make(__('filament.site')),
                        TableColumn::make(__('filament.project')),
                        TableColumn::make(__('filament.position')),
                        TableColumn::make(__('filament.supervisor')),
                    ])
                    ->schema([
                        TextEntry::make('date')
                            ->label(__('filament.date'))
                            ->date(),
                        TextEntry::make('site.name')
                            ->label(__('filament.site')),
                        TextEntry::make('project.name')
                            ->label(__('filament.project')),
                        TextEntry::make('position.details')
                            ->label(__('filament.position')),
                        TextEntry::make('supervisor.name')
                            ->label(__('filament.supervisor')),
                    ]),
            ]);
    }

    protected static function pastTerminationsSection(): Section
    {
        return Section::make(__('filament.terminations_history'))
            ->columns([
                'default' => 1,
            ])
            ->collapsible()
            ->collapsed(fn (Employee $record) => ! $record->terminations()->exists())
            ->columnSpanFull()
            ->components([
                RepeatableEntry::make('terminations')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 4,
                    ])
                    ->table([
                        TableColumn::make(__('filament.date')),
                        TableColumn::make(__('filament.termination_type')),
                        TableColumn::make(__('filament.is_rehirable')),
                        TableColumn::make(__('filament.comment')),
                    ])
                    ->schema([
                        TextEntry::make('date')
                            ->label(__('filament.date'))
                            ->date(),
                        TextEntry::make('termination_type')
                            ->label(__('filament.termination_type'))
                            ->formatStateUsing(fn (string $state): string => __('enums.termination.'.$state)),
                        IconEntry::make('is_rehirable')
                            ->boolean()
                            ->label(__('filament.is_rehirable')),
                        TextEntry::make('comment')
                            ->label(__('filament.comment'))
                            ->wrap()
                            ->limit(50)
                            ->tooltip(fn ($record) => $record->comment),
                    ]),
            ]);
    }

    protected static function last30DaysAbsencesSection(): Section
    {
        return Section::make(__('filament.last_30_days_absences'))
            ->columns(1)
            ->collapsible()
            ->columnSpanFull()
            ->components([
                RepeatableEntry::make('absences')
                    ->state(function ($record) {
                        return $record->absences()
                            ->latest('date', 'desc')
                            ->whereDate('date', '>=', Carbon::now()->subDays(30))
                            ->get();
                    })
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 4,
                    ])
                    ->table([
                        TableColumn::make(__('filament.date')),
                        TableColumn::make(__('filament.absence_type')),
                        TableColumn::make(__('filament.status')),
                        TableColumn::make(__('filament.comment')),
                    ])
                    ->schema([
                        TextEntry::make('date')
                            ->label(__('filament.date'))
                            ->date(),
                        TextEntry::make('absenceType.name')
                            ->label(__('filament.absence_type')),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn ($record) => $record->status->getColor()),
                        TextEntry::make('comment')
                            ->label(__('filament.comment'))
                            ->wrap()
                            ->limit(50)
                            ->tooltip(fn ($record) => $record->comment),
                    ]),
            ]);
    }
}
