<?php

namespace App\Filament\HumanResource\Resources\Employees\Schemas;

use App\Models\Employee;
use Carbon\Carbon;
use Filament\Infolists\Components\IconEntry;
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
                ->label('Photo')
                ->collection(Employee::PROFILE_PHOTO_COLLECTION)
                ->conversion(Employee::PROFILE_PHOTO_THUMBNAIL_CONVERSION)
                ->defaultImageUrl(fn (Employee $record): string => $record->getProfilePhotoPlaceholderUrl())
                ->circular()
                ->openUrlInNewTab()
                ->url(fn (Employee $record) => $record->getProfilePhotoUrl()),
            TextEntry::make('id')
                ->label('ID'),
            TextEntry::make('full_name')
                ->placeholder('-'),
            TextEntry::make('status')
                ->badge(),
            TextEntry::make('personal_id_type')
                ->badge()
                ->color(fn ($value) => $value?->getColor()),
            TextEntry::make('personal_id'),
            TextEntry::make('date_of_birth')
                ->date(),
            TextEntry::make('gender')
                ->badge()
                ->icon(fn ($value) => $value?->getIcon())
                ->color(fn ($value) => $value?->getColor()),
            TextEntry::make('cellphone'),
            IconEntry::make('has_kids')
                ->boolean(),
            TextEntry::make('citizenship.name')
                ->label('Citizenship')
                ->icon('heroicon-o-flag'),
            TextEntry::make('internal_id'),
            TextEntry::make('deleted_at')
                ->dateTime()
                ->visible(fn (Employee $record): bool => $record->trashed()),
            TextEntry::make('created_at')
                ->dateTime()
                ->placeholder('-'),
            TextEntry::make('updated_at')
                ->dateTime()
                ->placeholder('-'),
        ];
    }

    private static function bankAccountInformationSection(): Section
    {
        return Section::make('Bank Account Information')
            ->columns([
                'default' => 1,
                'sm' => 2,
            ])
            ->collapsible()
            ->collapsed(fn (Employee $record) => ! $record->bankAccount()->exists())
            ->columnSpanFull()
            ->components([
                TextEntry::make('bankAccount.bank.name')
                    ->label('Bank Name'),
                TextEntry::make('bankAccount.account')
                    ->label('Account Number'),
            ]);
    }

    private static function socialSecuritySection(): Section
    {
        return Section::make('Social Security Information')
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
                    ->label('AFP'),
                TextEntry::make('socialSecurity.ars.name')
                    ->label('ARS'),
                TextEntry::make('socialSecurity.number')
                    ->label('TSS Number'),
            ]);
    }

    private static function jobInformationSection(): Section
    {
        return Section::make('Job Information')
            ->columns([
                'default' => 1,
                'sm' => 2,
            ])
            ->collapsible()
            ->columnSpanFull()
            ->components([
                TextEntry::make('hired_at')
                    ->date(),
                TextEntry::make('site.name')
                    ->label('Site'),
                TextEntry::make('project.name')
                    ->label('Project'),
                TextEntry::make('position.name')
                    ->label('Position'),
                TextEntry::make('supervisor.name')
                    ->label('Supervisor'),
            ]);
    }

    private static function pastSuspensionsSection(): Section
    {
        return Section::make('Suspensions History')
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
                        TableColumn::make('Suspension Type'),
                        TableColumn::make('Starts At'),
                        TableColumn::make('Ends At'),
                        TableColumn::make('Duration (Days)'),
                        TableColumn::make('Status'),
                        TableColumn::make('Comment'),
                    ])
                    ->schema([
                        TextEntry::make('suspensionType.name')
                            ->label('Suspension Type'),
                        TextEntry::make('starts_at')
                            ->date(),
                        TextEntry::make('ends_at')
                            ->date(),
                        TextEntry::make('duration')
                            ->label('Duration (Days)'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn ($record) => $record->status->getColor()),
                        TextEntry::make('comment')
                            ->wrap()
                            ->label('Comment')
                            ->limit(50)
                            ->tooltip(fn ($record) => $record->comment),
                    ]),
            ]);
    }

    private static function pastHiresSection(): Section
    {
        return Section::make('Hires History')
            ->columns([
                'default' => 1,
                // 'sm' => 2,
                // 'md' => 5,
                // 'lg' => 4,
                // 'xl' => 5,
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
                        TableColumn::make('Date'),
                        TableColumn::make('Site'),
                        TableColumn::make('Project'),
                        TableColumn::make('Position'),
                        TableColumn::make('Supervisor'),
                    ])
                    ->schema([
                        TextEntry::make('date')
                            ->date(),
                        TextEntry::make('site.name')
                            ->label('Site'),
                        TextEntry::make('project.name')
                            ->label('Project'),
                        TextEntry::make('position.name')
                            ->label('Position'),
                        TextEntry::make('supervisor.name')
                            ->label('Supervisor'),
                    ]),
            ]);
    }

    protected static function pastTerminationsSection(): Section
    {
        return Section::make('Terminations History')
            ->columns([
                'default' => 1,
                // 'sm' => 2,
                // 'md' => 5,
                // 'lg' => 4,
                // 'xl' => 5,
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
                        TableColumn::make('Date'),
                        TableColumn::make('Termination Type'),
                        TableColumn::make('Is Rehirable'),
                        TableColumn::make('Comment'),
                    ])
                    ->schema([
                        TextEntry::make('date')
                            ->date(),
                        TextEntry::make('termination_type')
                            ->label('Termination Type'),
                        IconEntry::make('is_rehirable')
                            ->boolean()
                            ->label('Is Rehirable'),
                        TextEntry::make('comment')
                            ->label('Comment')
                            ->wrap()
                            ->limit(50)
                            ->tooltip(fn ($record) => $record->comment),
                    ]),
            ]);
    }

    protected static function last30DaysAbsencesSection(): Section
    {
        return Section::make('Last 30 Days Absences')
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
                        TableColumn::make('Date'),
                        TableColumn::make('Absence Type'),
                        TableColumn::make('Status'),
                        TableColumn::make('Comment'),
                    ])
                    ->schema([
                        TextEntry::make('date')
                            ->date(),
                        TextEntry::make('absenceType.name')
                            ->label('Absence Type'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn ($record) => $record->status->getColor()),
                        TextEntry::make('comment')
                            ->label('Comment')
                            ->wrap()
                            ->limit(50)
                            ->tooltip(fn ($record) => $record->comment),
                    ]),
            ]);
    }
}
