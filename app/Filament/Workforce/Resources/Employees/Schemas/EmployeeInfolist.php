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
                    ->label(__('filament.profile_photo'))
                    ->collection(Employee::PROFILE_PHOTO_COLLECTION)
                    ->conversion(Employee::PROFILE_PHOTO_THUMBNAIL_CONVERSION)
                    ->defaultImageUrl(fn (Employee $record): string => $record->getProfilePhotoPlaceholderUrl())
                    ->circular(),
                TextEntry::make('id')
                    ->label(__('filament.id'))
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
                    ->label(__('filament.email')),
                TextEntry::make('status')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('gender')
                    ->badge(),
                TextEntry::make('citizenship.name')
                    ->label(__('filament.citizenship')),

                Section::make(__('filament.job_information'))
                    ->columns(5)
                    ->columnSpanFull()
                    ->components([
                        TextEntry::make('hired_at')
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
