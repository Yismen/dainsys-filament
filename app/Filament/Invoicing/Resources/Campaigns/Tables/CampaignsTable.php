<?php

namespace App\Filament\Invoicing\Resources\Campaigns\Tables;

use App\Enums\RevenueTypes;
use App\Models\Project;
use App\Models\Source;
use App\Services\ModelListService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
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
                TextColumn::make('name')
                    ->label(__('filament.name'))
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('project.name')
                    ->label(__('filament.project'))
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('invoiceAgent.name')
                    ->label(__('filament.agent'))
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->placeholder(__('filament.unassigned')),
                TextColumn::make('description')
                    ->label(__('filament.description'))
                    ->limit(40)
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
                TrashedFilter::make()
                    ->label(__('filament.trashed')),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('filament.create_campaign'))
                    ->modalHeading(__('filament.create_campaign')),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(__('filament.view'))
                    ->modalHeading(__('filament.view_campaign')),
                EditAction::make()
                    ->label(__('filament.edit'))
                    ->modalHeading(__('filament.edit_campaign')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('filament.delete')),
                    ForceDeleteBulkAction::make()
                        ->label(__('filament.force_delete')),
                    RestoreBulkAction::make()
                        ->label(__('filament.restore')),
                ])->label(__('filament.bulk_actions')),
            ]);
    }
}
