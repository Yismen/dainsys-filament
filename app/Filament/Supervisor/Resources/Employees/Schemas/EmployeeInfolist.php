<?php

namespace App\Filament\Supervisor\Resources\Employees\Schemas;

use App\Models\Employee;
use Filament\Infolists\Components\ImageEntry;
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
                SpatieMediaLibraryImageEntry::make('profile_photo')
                    ->hiddenLabel()
                    ->collection(Employee::PROFILE_PHOTO_COLLECTION)
                    ->conversion(Employee::PROFILE_PHOTO_THUMBNAIL_CONVERSION)
                    ->defaultImageUrl(fn (Employee $record): string => $record->getProfilePhotoPlaceholderUrl())
                    ->circular()
                    ->columnSpanFull(),
                TextEntry::make('full_name')
                    ->label('Full Name'),
                TextEntry::make('personal_id')
                    ->label('Personal ID'),
                TextEntry::make('email')
                    ->label('Email'),
                TextEntry::make('cellphone')
                    ->label('Phone'),
                TextEntry::make('date_of_birth')
                    ->date('M d, Y')
                    ->label('Date of Birth'),
                TextEntry::make('status')
                    ->badge()
                    ->color(fn ($state) => $state->getColor()),
                TextEntry::make('citizenship.name')
                    ->label('Citizenship'),
                TextEntry::make('address')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->label('Created'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->label('Updated'),
                Section::make('Employment Information')
                    ->label('Employment Information')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make([
                            'default' => 2,
                            'sm' => 3,
                            'lg' => 4,
                        ])
                            ->schema([
                                TextEntry::make('position.name')
                                    ->label('Position'),
                                TextEntry::make('position.department.name')
                                    ->label('Department'),
                                TextEntry::make('hired_at')
                                    ->date('M d, Y')
                                    ->label('Date Hired'),
                                TextEntry::make('internal_id')
                                    ->label('Punch ID'),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}
