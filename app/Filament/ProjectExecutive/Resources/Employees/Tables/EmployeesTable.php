<?php

namespace App\Filament\ProjectExecutive\Resources\Employees\Tables;

use App\Enums\EmployeeStatuses;
use App\Models\Employee;
use App\Models\Project;
use App\Services\ModelListService;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('full_name')
            ->columns([
                SpatieMediaLibraryImageColumn::make('profile_photo')
                    ->label('Photo')
                    ->collection(Employee::PROFILE_PHOTO_COLLECTION)
                    ->conversion(Employee::PROFILE_PHOTO_THUMBNAIL_CONVERSION)
                    ->defaultImageUrl(fn (Employee $record) => $record->getProfilePhotoPlaceholderUrl())
                    ->circular(),
                TextColumn::make('full_name')
                    ->label('Employee')
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                TextColumn::make('project.name')
                    ->label('Project')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('position.name')
                    ->label('Position')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('site.name')
                    ->label('Site')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('hired_at')
                    ->label('Hired Date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('project_id')
                    ->label('Project')
                    ->options(function (): array {
                        $managerId = Auth::id();

                        if (! $managerId) {
                            return [];
                        }

                        return ModelListService::make(
                            model: Project::query()->where('manager_id', $managerId),
                        );
                    })
                    ->searchable(),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(EmployeeStatuses::class)
                    ->searchable(),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
