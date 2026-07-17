<?php

namespace App\Filament\Employee\Pages;

use App\Models\Employee;
use BackedEnum;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\Auth;

class SelfProfile extends Page implements HasInfolists
{
    use InteractsWithInfolists;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Profile';

    protected static ?string $title = 'My Profile';

    protected static ?int $navigationSort = 1;

    public ?Employee $employee = null;

    public function mount(): void
    {
        $user = Auth::user();

        if (! $user->employee_id) {
            abort(403, 'No employee record found.');
        }

        $this->employee = Employee::with([
            'citizenship',
            'supervisor',
            'project',
            'position',
            'site',
            'socialSecurity.ars',
            'socialSecurity.afp',
            'hires.site',
            'hires.project',
            'hires.position',
            'hires.supervisor',
            'terminations',
            'suspensions.suspensionType',
            'media',
        ])->findOrFail($user->employee_id);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->record($this->employee)
            ->components([
                Section::make(__('filament.personal_information'))
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('profile_photo')
                            ->hiddenLabel()
                            ->collection(Employee::PROFILE_PHOTO_COLLECTION)
                            ->conversion(Employee::PROFILE_PHOTO_THUMBNAIL_CONVERSION)
                            ->defaultImageUrl(fn (Employee $record): string => $record->getProfilePhotoPlaceholderUrl())
                            ->circular()
                            ->columnSpanFull(),
                        TextEntry::make('full_name')
                            ->label(__('filament.full_name'))
                            ->weight(FontWeight::Bold),
                        TextEntry::make('personal_id_type')
                            ->label(__('filament.id_type'))
                            ->badge(),
                        TextEntry::make('personal_id')
                            ->label(__('filament.personal_id')),
                        TextEntry::make('internal_id')
                            ->label(__('filament.internal_employee_id')),
                        TextEntry::make('date_of_birth')
                            ->label(__('filament.date_of_birth'))
                            ->date(),
                        TextEntry::make('gender')
                            ->badge(),
                        TextEntry::make('citizenship.name')
                            ->label(__('filament.citizenship')),
                        TextEntry::make('has_kids')
                            ->label(__('filament.has_kids'))
                            ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                    ])
                    ->columns(2),

                Section::make(__('filament.contact_information'))
                    ->schema([
                        TextEntry::make('cellphone')
                            ->label(__('filament.cellphone')),
                        TextEntry::make('secondary_phone')
                            ->label(__('filament.secondary_phone'))
                            ->placeholder('-'),
                        TextEntry::make('email')
                            ->label(__('filament.email'))
                            ->placeholder('-'),
                        TextEntry::make('address')
                            ->label(__('filament.address'))
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make(__('filament.current_employment'))
                    ->schema([
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('site.name')
                            ->label(__('filament.site'))
                            ->placeholder('-'),
                        TextEntry::make('project.name')
                            ->label(__('filament.project'))
                            ->placeholder('-'),
                        TextEntry::make('position.name')
                            ->label(__('filament.position'))
                            ->placeholder('-'),
                        TextEntry::make('supervisor.name')
                            ->label(__('filament.supervisor'))
                            ->placeholder('-'),
                        TextEntry::make('hired_at')
                            ->label(__('filament.hired_date'))
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2),

                Section::make(__('filament.social_security_tss'))
                    ->schema([
                        TextEntry::make('socialSecurity.number')
                            ->label(__('filament.tss_number'))
                            ->placeholder('-'),
                        TextEntry::make('socialSecurity.ars.name')
                            ->label(__('filament.ars'))
                            ->placeholder('-'),
                        TextEntry::make('socialSecurity.afp.name')
                            ->label(__('filament.afp'))
                            ->placeholder('-'),
                    ])
                    ->columns(3)
                    ->visible(fn () => $this->employee->socialSecurity !== null),

                Section::make(__('filament.employment_history'))
                    ->schema([
                        RepeatableEntry::make('hires')
                            ->label(__('filament.hires'))
                            ->schema([
                                TextEntry::make('date')
                                    ->label(__('filament.hire_date'))
                                    ->dateTime(),
                                TextEntry::make('site.name')
                                    ->label(__('filament.site')),
                                TextEntry::make('project.name')
                                    ->label(__('filament.project')),
                                TextEntry::make('position.name')
                                    ->label(__('filament.position')),
                                TextEntry::make('position.salary')
                                    ->label(__('filament.salary'))
                                    ->money('DOP'),
                                TextEntry::make('supervisor.name')
                                    ->label(__('filament.supervisor')),
                            ])
                            ->columns(3)
                            ->contained(false),
                    ])
                    ->collapsible()
                    ->visible(fn () => $this->employee->hires->count() > 0),

                Section::make(__('filament.termination_history'))
                    ->schema([
                        RepeatableEntry::make('terminations')
                            ->label(__('filament.terminations'))
                            ->schema([
                                TextEntry::make('date')
                                    ->label(__('filament.termination_date'))
                                    ->dateTime(),
                                TextEntry::make('termination_type')
                                    ->label(__('filament.type'))
                                    ->placeholder('-'),
                            ])
                            ->columns(2)
                            ->contained(false),
                    ])
                    ->collapsible()
                    ->visible(fn () => $this->employee->terminations->count() > 0),

                Section::make(__('filament.suspension_history'))
                    ->schema([
                        RepeatableEntry::make('suspensions')
                            ->label(__('filament.suspensions'))
                            ->schema([
                                TextEntry::make('suspensionType.name')
                                    ->label(__('filament.type')),
                                TextEntry::make('starts_at')
                                    ->label(__('filament.start_date'))
                                    ->dateTime(),
                                TextEntry::make('ends_at')
                                    ->label(__('filament.end_date'))
                                    ->dateTime()
                                    ->placeholder('-'),
                            ])
                            ->columns(3)
                            ->contained(false),
                    ])
                    ->collapsible()
                    ->visible(fn () => $this->employee->suspensions->count() > 0),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function content(Schema $schema): Schema
    {
        return $this->infolist($schema);
    }
}
