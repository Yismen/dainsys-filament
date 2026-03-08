<?php

namespace App\Filament\Workforce\Resources\Employees\Schemas;

use App\Models\Employee;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SpatieMediaLibraryImageEntry::make('profile_photo')
                    ->label('Photo')
                    ->collection(Employee::PROFILE_PHOTO_COLLECTION)
                    ->conversion(Employee::PROFILE_PHOTO_THUMBNAIL_CONVERSION)
                    ->defaultImageUrl(fn (Employee $record): string => $record->getProfilePhotoPlaceholderUrl())
                    ->circular(),
                TextEntry::make('id')
                    ->label('ID')
                    ->columnSpanFull(),
                TextEntry::make('full_name')
                    ->placeholder('-'),
                TextEntry::make('personal_id_type')
                    ->badge(),
                TextEntry::make('personal_id'),
                TextEntry::make('date_of_birth')
                    ->date(),
                TextEntry::make('cellphone'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('status')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('gender')
                    ->badge(),
                TextEntry::make('citizenship.name')
                    ->label('Citizenship'),

                Section::make('Job Information')
                    ->columns(5)
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
                    ]),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Employee $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
