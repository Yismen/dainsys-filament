<?php

namespace App\Filament\HumanResource\Resources\Employees\Tables;

use App\Actions\Filament\Employee\HireEmployeeAction;
use App\Actions\Filament\Employee\SuspendEmployeeAction;
use App\Actions\Filament\Employee\TerminateEmployeeAction;
use App\Exports\Filament\EmployeeExporter;
use App\Filament\Admin\Resources\Employees\Tables\EmployeeTableFilters;
use App\Models\Employee;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
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
                    ->wrapHeader()
                    ->sortable()
                    ->searchable()
                    ->copyable(),
                TextColumn::make('personal_id')
                    ->label(__('filament.personal_id'))
                    ->sortable()
                    ->searchable()
                    ->copyable(),
                TextColumn::make('date_of_birth')
                    ->label(__('filament.date_of_birth'))
                    ->date()
                    ->sortable()
                    ->wrap()
                    ->wrapHeader(),
                TextColumn::make('cellphone')
                    ->label(__('filament.cellphone'))
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('filament.status'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('gender')
                    ->label(__('filament.gender'))
                    ->badge()
                    ->sortable()
                    ->searchable(),
                IconColumn::make('has_kids')
                    ->label(__('filament.has_kids'))
                    ->sortable()
                    ->boolean()
                    ->wrap()
                    ->wrapHeader(),
                TextColumn::make('internal_id')
                    ->label(__('filament.internal_id'))
                    ->sortable()
                    ->searchable()
                    ->copyable(),
                TextColumn::make('citizenship.name')
                    ->label(__('filament.citizenship'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('site.name')
                    ->label(__('filament.site'))
                    ->wrap()
                    ->wrapHeader()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('project.name')
                    ->label(__('filament.project'))
                    ->wrap()
                    ->wrapHeader()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('supervisor.name')
                    ->label(__('filament.supervisor'))
                    ->wrap()
                    ->wrapHeader()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('position.details')
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
            ->filtersFormColumns(2)
            ->filtersFormWidth(Width::Large)
            ->recordActions([
                ViewAction::make()
                    ->record(fn () => Employee::withTrashed()->find(request()->route('record'))->load([
                        'hires' => [
                            'site',
                            'supervisor',
                            'project',
                            'position',
                        ],
                        'site',
                        'project',
                        'supervisor',
                        'position',
                    ]))
                    ->stickyModalFooter()
                    ->stickyModalHeader()
                    ->closeModalByClickingAway(false)
                    ->modalWidth(Width::SevenExtraLarge)
                    ->extraModalFooterActions([
                        HireEmployeeAction::make(),
                        TerminateEmployeeAction::make(),
                        SuspendEmployeeAction::make(),
                    ]),
                EditAction::make()
                    ->stickyModalFooter()
                    ->stickyModalHeader()
                    ->closeModalByClickingAway(false)
                    ->modalWidth(Width::SevenExtraLarge)
                    ->extraModalFooterActions([
                        HireEmployeeAction::make(),
                        TerminateEmployeeAction::make(),
                        SuspendEmployeeAction::make(),
                    ]),
            ])
            ->toolbarActions([
                ExportBulkAction::make()
                    ->color(Color::Teal)
                    ->exporter(EmployeeExporter::class)
                    ->deselectRecordsAfterCompletion()
                    ->icon(Heroicon::OutlinedDocumentArrowDown),
            ]);
    }
}
