<?php

namespace App\Filament\ProjectExecutive\Resources\Employees\Schemas;

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
                    ->label('Employee'),
                TextEntry::make('personal_id')
                    ->label('Personal ID'),
                TextEntry::make('email')
                    ->placeholder('-'),
                TextEntry::make('cellphone')
                    ->label('Phone')
                    ->placeholder('-'),
                TextEntry::make('project.name')
                    ->label('Project')
                    ->placeholder('-'),
                TextEntry::make('position.name')
                    ->label('Position')
                    ->placeholder('-'),
                TextEntry::make('site.name')
                    ->label('Site')
                    ->placeholder('-'),
                TextEntry::make('supervisor.name')
                    ->label('Supervisor')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('hired_at')
                    ->label('Hired Date')
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
