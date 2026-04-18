<?php

namespace App\Filament\Workforce\Resources\Employees\Tables;

use App\Actions\Filament\Employee\ResetEmployeePasswordAction;
use App\Filament\Admin\Resources\Employees\Tables\EmployeeTableFilters;
use App\Models\Employee;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
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
                    ->defaultImageUrl(fn (Employee $record): string => $record->getProfilePhotoPlaceholderUrl())
                    ->circular(),
                TextColumn::make('id')
                    ->label(__('filament.id'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('full_name')
                    ->label(__('filament.full_name'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cellphone')
                    ->label(__('filament.cellphone'))
                    ->wrap()
                    ->searchable(),
                TextColumn::make('personal_id')
                    ->label(__('filament.personal_id'))
                    ->wrap()
                    ->copyable()
                    ->searchable(),
                TextColumn::make('citizenship.name')
                    ->label(__('filament.citizenship'))
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gender')
                    ->label(__('filament.gender'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('filament.status'))
                    ->sortable()
                    ->searchable(),
                IconColumn::make('has_kids')
                    ->label(__('filament.has_kids'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('site.name')
                    ->label(__('filament.site'))
                    ->wrap()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('project.name')
                    ->label(__('filament.project'))
                    ->wrap()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('supervisor.name')
                    ->label(__('filament.supervisor'))
                    ->wrap()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('position.name')
                    ->label(__('filament.position'))
                    ->wrap()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('filament.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('filament.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                ...EmployeeTableFilters::get(),
            ])
            ->filtersFormColumns(3)
            ->filtersFormWidth(Width::FourExtraLarge)
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->modalWidth(Width::SevenExtraLarge),
                ResetEmployeePasswordAction::make()
                    ->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
