<?php

namespace App\Filament\Supervisor\Resources\Employees\Tables;

use App\Actions\Filament\Supervisor\RequestEmployeeActivityAction;
use App\Actions\Filament\Supervisor\RequestEmployeeDowntimeAction;
use App\Enums\EmployeeStatuses;
use App\Models\Employee;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('full_name')
            ->columns([
                SpatieMediaLibraryImageColumn::make('profile_photo')
                    ->label(__('filament.profile_photo'))
                    ->collection(Employee::PROFILE_PHOTO_COLLECTION)
                    ->conversion(Employee::PROFILE_PHOTO_THUMBNAIL_CONVERSION)
                    ->defaultImageUrl(fn ($record) => $record->getProfilePhotoPlaceholderUrl())
                    ->circular(),
                TextColumn::make('full_name')
                    ->label(__('filament.employee'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('filament.email'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cellphone')
                    ->label(__('filament.phone'))
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('filament.status'))
                    ->badge()
                    ->colors([
                        'success' => EmployeeStatuses::Hired,
                        'warning' => EmployeeStatuses::Suspended,
                        'info' => EmployeeStatuses::Created,
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                RequestEmployeeActivityAction::make(),
                RequestEmployeeDowntimeAction::make(),
            ]);
    }
}
