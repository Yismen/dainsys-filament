<?php

namespace App\Filament\HumanResource\Resources\Employees\Tables;

use App\Enums\EmployeeStatuses;
use App\Exports\Filament\EmployeeExporter;
use App\Filament\Admin\Resources\Employees\Tables\EmployeeTableFilters;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Project;
use App\Models\Site;
use App\Models\Supervisor;
use App\Services\ModelListService;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

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
                    ->defaultImageUrl(fn (Employee $record): string => $record->getProfilePhotoPlaceholderUrl())
                    ->circular(),
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('full_name')
                    ->wrap()
                    ->sortable()
                    ->searchable()
                    ->copyable(),
                TextColumn::make('personal_id')
                    ->sortable()
                    ->searchable()
                    ->copyable(),
                TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
                TextColumn::make('cellphone')
                    ->searchable(),
                TextColumn::make('status')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('gender')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                IconColumn::make('has_kids')
                    ->sortable()
                    ->boolean(),
                TextColumn::make('internal_id')
                    ->sortable()
                    ->searchable()
                    ->copyable(),
                TextColumn::make('citizenship.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('site.name')
                    ->wrap()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('project.name')
                    ->wrap()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('supervisor.name')
                    ->wrap()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('position.name')
                    ->wrap()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
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
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkAction::make('hire_employees')
                    ->label('Hire Employees')
                    ->color(Color::Indigo)
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('site_id')
                                    ->required()
                                    ->searchable()
                                    ->options(ModelListService::make(Site::query())),
                                Select::make('project_id')
                                    ->searchable()
                                    ->required()
                                    ->options(ModelListService::make(Project::query())),
                                Select::make('position_id')
                                    ->searchable()
                                    ->required()
                                    ->options(ModelListService::make(Position::query())),
                                Select::make('supervisor_id')
                                    ->required()
                                    ->options(ModelListService::make(Supervisor::query()))
                                    ->searchable(),
                                DateTimePicker::make('hired_at')
                                    ->required()
                                    ->default(now()),
                            ]),
                    ])
                    ->successNotificationTitle('Employees hired')
                    ->deselectRecordsAfterCompletion()
                    ->action(function (BulkAction $bulkAction, array $data, Collection $records): void {
                        $records = $records->filter(fn ($record) => $record->status === EmployeeStatuses::Created);

                        foreach ($records as $record) {
                            $record->hires()
                                ->create([
                                    'site_id' => $data['site_id'] ?? null,
                                    'project_id' => $data['project_id'] ?? null,
                                    'position_id' => $data['position_id'] ?? null,
                                    'supervisor_id' => $data['supervisor_id'] ?? null,
                                    'date' => $data['hired_at'] ?? now(),
                                ]);
                        }
                    }),
                ExportBulkAction::make()
                    ->color(Color::Teal)
                    ->exporter(EmployeeExporter::class),
            ]);
    }
}
