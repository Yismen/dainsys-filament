<?php

namespace App\Filament\OperationsDirector\Resources\Employees\Schemas;

use App\Models\Employee;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                SpatieMediaLibraryImageEntry::make('profile_photo')
                    ->hiddenLabel()
                    ->collection(Employee::PROFILE_PHOTO_COLLECTION)
                    ->conversion(Employee::PROFILE_PHOTO_THUMBNAIL_CONVERSION)
                    ->defaultImageUrl(fn (Employee $record): string => $record->getProfilePhotoPlaceholderUrl())
                    ->circular()
                    ->columnSpanFull(),
                TextEntry::make('full_name')
                    ->label(__('filament.employee')),
                TextEntry::make('personal_id')
                    ->label(__('filament.personal_id')),
                TextEntry::make('email')
                    ->placeholder('-'),
                TextEntry::make('cellphone')
                    ->label(__('filament.phone'))
                    ->placeholder('-'),
                TextEntry::make('project.name')
                    ->label(__('filament.project'))
                    ->placeholder('-'),
                TextEntry::make('position.details')
                    ->label(__('filament.position'))
                    ->placeholder('-'),
                TextEntry::make('site.name')
                    ->label(__('filament.site'))
                    ->placeholder('-'),
                TextEntry::make('supervisor.name')
                    ->label(__('filament.supervisor'))
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('hired_at')
                    ->label(__('filament.hired_at'))
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
