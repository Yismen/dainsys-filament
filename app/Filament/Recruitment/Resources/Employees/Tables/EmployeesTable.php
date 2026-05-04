<?php

namespace App\Filament\Recruitment\Resources\Employees\Tables;

use App\Enums\EmployeeStatuses;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Project;
use App\Models\Site;
use App\Models\Supervisor;
use App\Services\ModelListService;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('full_name')
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament.id'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('full_name')
                    ->label(__('filament.full_name'))
                    ->wrap()
                    ->wrapHeader()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('personal_id')
                    ->label(__('filament.personal_id'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('filament.status'))
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('site.name')
                    ->label(__('filament.site'))
                    ->wrap()
                    ->wrapHeader()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('project.name')
                    ->label(__('filament.project'))
                    ->wrap()
                    ->wrapHeader()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('position.details')
                    ->label(__('filament.position'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('supervisor.name')
                    ->label(__('filament.supervisor'))
                    ->wrap()
                    ->wrapHeader()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('hired_at')
                    ->label(__('filament.hired_at'))
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament.status'))
                    ->options(EmployeeStatuses::class)
                    ->searchable(),
                SelectFilter::make('site_id')
                    ->label(__('filament.site'))
                    ->options(ModelListService::make(model: Site::query(), value_field: 'name'))
                    ->searchable(),
                SelectFilter::make('project_id')
                    ->label(__('filament.project'))
                    ->options(ModelListService::make(model: Project::query(), value_field: 'name'))
                    ->searchable(),
                SelectFilter::make('position_id')
                    ->label(__('filament.position'))
                    ->options(ModelListService::make(model: Position::query(), value_field: 'name'))
                    ->searchable(),
                SelectFilter::make('supervisor_id')
                    ->label(__('filament.supervisor'))
                    ->options(ModelListService::make(model: Supervisor::query(), value_field: 'name'))
                    ->searchable(),
            ])
            ->filtersFormColumns(2)
            ->filtersFormWidth(Width::Large)
            ->recordActions([
                ViewAction::make()
                    ->label(__('filament.view'))
                    ->stickyModalFooter()
                    ->stickyModalHeader()
                    ->closeModalByClickingAway(false)
                    ->modalWidth(Width::SevenExtraLarge)
                    ->record(fn () => Employee::withTrashed()->find(request()->route('record'))->load([
                        'site',
                        'project',
                        'position',
                        'supervisor',
                        'citizenship',
                        'bankAccount.bank',
                        'socialSecurity.afp',
                        'socialSecurity.ars',
                        'suspensions.suspensionType',
                        'absences',
                        'hires.site',
                        'hires.project',
                        'hires.position',
                        'hires.supervisor',
                        'terminations',
                    ])),
            ]);
    }
}
