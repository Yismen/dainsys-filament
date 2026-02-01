<?php

namespace App\Filament\Employee\Pages;

use App\Models\Employee;
use BackedEnum;
use Filament\Infolists\Components\RepeatableEntry;
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
        ])->findOrFail($user->employee_id);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->record($this->employee)
            ->components([
                Section::make('Personal Information')
                    ->schema([
                        TextEntry::make('full_name')
                            ->label('Full Name')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('personal_id_type')
                            ->label('ID Type')
                            ->badge(),
                        TextEntry::make('personal_id')
                            ->label('Personal ID'),
                        TextEntry::make('internal_id')
                            ->label('Internal Employee ID'),
                        TextEntry::make('date_of_birth')
                            ->label('Date of Birth')
                            ->date(),
                        TextEntry::make('gender')
                            ->badge(),
                        TextEntry::make('citizenship.name')
                            ->label('Citizenship'),
                        TextEntry::make('has_kids')
                            ->label('Has Kids')
                            ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                    ])
                    ->columns(2),

                Section::make('Contact Information')
                    ->schema([
                        TextEntry::make('cellphone')
                            ->label('Cellphone'),
                        TextEntry::make('secondary_phone')
                            ->label('Secondary Phone')
                            ->placeholder('-'),
                        TextEntry::make('email')
                            ->label('Email')
                            ->placeholder('-'),
                        TextEntry::make('address')
                            ->label('Address')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Current Employment')
                    ->schema([
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('site.name')
                            ->label('Site')
                            ->placeholder('-'),
                        TextEntry::make('project.name')
                            ->label('Project')
                            ->placeholder('-'),
                        TextEntry::make('position.name')
                            ->label('Position')
                            ->placeholder('-'),
                        TextEntry::make('supervisor.name')
                            ->label('Supervisor')
                            ->placeholder('-'),
                        TextEntry::make('hired_at')
                            ->label('Hired Date')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2),

                Section::make('Social Security (TSS)')
                    ->schema([
                        TextEntry::make('socialSecurity.number')
                            ->label('TSS Number')
                            ->placeholder('-'),
                        TextEntry::make('socialSecurity.ars.name')
                            ->label('ARS')
                            ->placeholder('-'),
                        TextEntry::make('socialSecurity.afp.name')
                            ->label('AFP')
                            ->placeholder('-'),
                    ])
                    ->columns(3)
                    ->visible(fn () => $this->employee->socialSecurity !== null),

                Section::make('Employment History')
                    ->schema([
                        RepeatableEntry::make('hires')
                            ->label('Hires')
                            ->schema([
                                TextEntry::make('date')
                                    ->label('Hire Date')
                                    ->dateTime(),
                                TextEntry::make('site.name')
                                    ->label('Site'),
                                TextEntry::make('project.name')
                                    ->label('Project'),
                                TextEntry::make('position.name')
                                    ->label('Position'),
                                TextEntry::make('position.salary')
                                    ->label('Salary')
                                    ->money('DOP'),
                                TextEntry::make('supervisor.name')
                                    ->label('Supervisor'),
                            ])
                            ->columns(3)
                            ->contained(false),
                    ])
                    ->collapsible()
                    ->visible(fn () => $this->employee->hires->count() > 0),

                Section::make('Termination History')
                    ->schema([
                        RepeatableEntry::make('terminations')
                            ->label('Terminations')
                            ->schema([
                                TextEntry::make('date')
                                    ->label('Termination Date')
                                    ->dateTime(),
                                TextEntry::make('termination_type')
                                    ->label('Type')
                                    ->placeholder('-'),
                            ])
                            ->columns(2)
                            ->contained(false),
                    ])
                    ->collapsible()
                    ->visible(fn () => $this->employee->terminations->count() > 0),

                Section::make('Suspension History')
                    ->schema([
                        RepeatableEntry::make('suspensions')
                            ->label('Suspensions')
                            ->schema([
                                TextEntry::make('suspensionType.name')
                                    ->label('Type'),
                                TextEntry::make('starts_at')
                                    ->label('Start Date')
                                    ->dateTime(),
                                TextEntry::make('ends_at')
                                    ->label('End Date')
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
