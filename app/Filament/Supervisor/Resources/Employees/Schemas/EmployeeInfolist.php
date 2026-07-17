<?php

namespace App\Filament\Supervisor\Resources\Employees\Schemas;

use App\Models\Employee;
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
                    ->label(__('filament.full_name')),
                TextEntry::make('personal_id')
                    ->label(__('filament.personal_id')),
                TextEntry::make('email')
                    ->label(__('filament.email')),
                TextEntry::make('cellphone')
                    ->label(__('filament.phone')),
                TextEntry::make('date_of_birth')
                    ->date('M d, Y')
                    ->label(__('filament.date_of_birth')),
                TextEntry::make('status')
                    ->badge()
                    ->color(fn ($state) => $state->getColor()),
                TextEntry::make('citizenship.name')
                    ->label(__('filament.citizenship')),
                TextEntry::make('address')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->label(__('filament.created_at')),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->label(__('filament.updated_at')),
                Section::make(__('filament.employment_information'))
                    ->label(__('filament.employment_information'))
                    ->columnSpanFull()
                    ->schema([
                        Grid::make([
                            'default' => 2,
                            'sm' => 3,
                            'lg' => 4,
                        ])
                            ->schema([
                                TextEntry::make('position.name')
                                    ->label(__('filament.position')),
                                TextEntry::make('position.department.name')
                                    ->label(__('filament.department')),
                                TextEntry::make('hired_at')
                                    ->date('M d, Y')
                                    ->label(__('filament.hired_at')),
                                TextEntry::make('internal_id')
                                    ->label(__('filament.punch_id')),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}
