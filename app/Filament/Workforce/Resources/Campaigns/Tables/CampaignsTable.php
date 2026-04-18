<?php

namespace App\Filament\Workforce\Resources\Campaigns\Tables;

use App\Enums\RevenueTypes;
use App\Models\Project;
use App\Models\Source;
use App\Services\ModelListService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CampaignsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament.id'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('filament.name'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('project.name')
                    ->label(__('filament.project'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('source.name')
                    ->label(__('filament.source'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('revenue_type')
                    ->label(__('filament.revenue_type'))
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('sph_goal')
                    ->label(__('filament.sph_goal'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('revenue_rate')
                    ->label(__('filament.revenue_rate'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('description')
                    ->label(__('filament.description'))
                    ->limit(30)
                    ->tooltip(fn (string $state) => $state)
                    ->wrap()
                    ->searchable(),
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
            ->filtersFormColumns(2)
            ->filtersFormWidth(Width::TwoExtraLarge)
            ->filters([
                SelectFilter::make('project_id')
                    ->label(__('filament.project'))
                    ->options(ModelListService::make(Project::query()))
                    ->searchable(),
                SelectFilter::make('source_id')
                    ->label(__('filament.source'))
                    ->options(ModelListService::make(Source::query()))
                    ->searchable(),
                SelectFilter::make('revenue_type')
                    ->label(__('filament.revenue_type'))
                    ->options(RevenueTypes::class),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
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
